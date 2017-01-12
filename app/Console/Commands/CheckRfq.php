<?php

namespace App\Console\Commands;

use App\Rfq;
use App\Rfqtask;
use phpQuery;
use Illuminate\Console\Command;
use App\Jobs\SendReminderEmail;

class CheckRfq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:rfq';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check for new quatation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tasks = Rfqtask::all();
        $a = 0;
        foreach($tasks as $task){
            if ((time()-strtotime($task->updated_at)) > $task->rate) {
                // echo $task->taskname . "\r\n";
                //待采集的目标页面
                $url = 'https://sourcing.alibaba.com/rfq_search_list.htm?searchText='.str_replace(' ','+',$task->keyword).'&recently=Y';
                phpQuery::newDocumentFile($url);
        
                //选择要采集的范围
                $artlist = pq(".list .item");
                // $a = 0;
                foreach($artlist as $li){
                    $temp = pq(pq($li)->find('.rfq-btn'))->attr('url');
                    // echo $temp;
                    $m = preg_match('/\d{10}/i',$temp,$result);
                    $n = Rfq::where('rfq_id', $result[0])->first();
                    if ($m && !$n) {
                        $rfq = new Rfq;
                        $rfq->rfq_id = $rfq_id = $result[0];;
                        $rfq->title = $title = trim(pq($li)->find('.item-title a')->text());
                        $rfq->desc = $content = trim(pq($li)->find('.item-digest')->text());
                        $rfq->quantity = $quantity = pq(pq($li)->find('.item-other-count span'))->attr('title');

                        $temp = pq(pq($li)->find('.item-info'))->attr('title');
                        preg_match('/\d{4}-\d{1,2}-\d{1,2}/i',$temp,$postdate);
                        $rfq->postdate = $postdate[0];

                        $rfq->country = $country = pq(pq($li)->find('.country-flag'))->attr('title');
                        $rfq->reached = pq($li)->find('.item-action-left span')->text();
                        $rfq->related = $task->keyword;
                        $rfq->save();
                        // echo $result[0] ."\r\n";

                        //推送到队列
                        $subject = "[" . $quantity . "]" . $title;

                        $data = [
                        'tmpl'=>'emails.remindrfq',
                        'rfq_id'=>$rfq_id,
                        'subject'=>$subject,
                        'content'=>$content,
                        'country'=>$country,
                        'mail_to'=>'colin@3gxun.com'
                        ];
                        $job = new SendReminderEmail($data);
                        dispatch($job);
                        ++$a;
                    } else {
                        continue;
                    }
                }
                //更新此次查询时间
                $task->updated_at = time();
                $task->save();
            }
        }
        echo $a;
    }
}
