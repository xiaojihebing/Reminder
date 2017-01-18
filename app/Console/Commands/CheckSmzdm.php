<?php

namespace App\Console\Commands;

use App\Smzdm;
use App\Smzdmtask;
use phpQuery;
use Illuminate\Console\Command;
use App\Jobs\SendReminderEmail;

class CheckSmzdm extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:smzdm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check if any new posts';

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
        $tasks = Smzdmtask::all();
        $a = 0;
        foreach($tasks as $task){
            if ((time()-strtotime($task->updated_at)) > $task->rate) {
                // echo $task->name . "\r\n";
                //待采集的目标页面
                phpQuery::newDocumentFile($task->rurl);
        
                //选择要采集的范围
                $lists = pq(".right-list-detail");
                // $a = 0;
                foreach($lists as $li){
                    $temp = pq($li)->find('.right-list-face a')->attr('href');
                    // echo $temp;
                    $m = preg_match('/\d{7}/i',$temp,$result);
                    $n = Smzdm::where('pid', $result[0])->first();
                    if ($m && !$n) {
                        $smzdm = new Smzdm;
                        $smzdm->url = $url = $temp;
                        $smzdm->pid = $pid = $result[0];
                        $smzdm->title = $title = pq($li)->find('.right-list-face img')->attr('alt');
                        $smzdm->price = $price = pq($li)->find('.red')->text();
                        $smzdm->content = $content = pq($li)->find('.right-list-info')->text();
                        $smzdm->purl = $purl = pq($li)->find('.buy a')->attr('href');
                        $smzdm->pdate = $pdate = pq($li)->find('.lrBot time')->text();
                        $smzdm->save();
                        // echo $pdate ."\r\n";continue;

                        //推送到队列
                        $subject = "[" . $task->name . "]" . $price;

                        $data = [
                        'tmpl'=>'emails.smzdm',
                        'pid'=>$pid,
                        'title'=>$title,
                        'subject'=>$subject,
                        'content'=>$content,
                        'purl'=>$purl,
                        'pdate'=>$pdate,
                        'mail_to'=>'gongxi@sooga.cn'
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
