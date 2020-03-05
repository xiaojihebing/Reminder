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
                preg_match_all('/data.push\(([\s\S]*?)\)\;/i', file_get_contents($url), $lists);

                foreach($lists[1] as $li){
                    // $li = str_replace(['\x2d','\x2a','\x20','\x3c','\x3e','\x2f'], ['-','*','<','>','/'], $li);
                    preg_match_all('/:(.*?),\r/i', str_replace('"', '', $li), $result);
                    // preg_match('/\d{10}/i', $result[1][11], $rfq_id);
                    
                    $title = trim(strip_tags($this->hextostr($result[1][3])));
                    // $content = trim(strip_tags($this->hextostr($result[1][4])));
                    $country = $result[1][6];
                    $count = RFQ::all()->count();

                    $rfql = RFQ::where('id','>', $count-500)->where('title', $title)->where('country', $country)->first();
                    //Carbon::now()->subDays(25)
                    
                    if ($rfql) {
                        continue;
                    } else {
                        // echo $rfq_id."\r\n";
                        // echo substr($rfq_id,0,24)."\r\n";
                        // echo trim(strip_tags($result[1][2]))."\r\n";
                        // echo trim(strip_tags($this->hextostr($result[1][4])))."\r\n";
                        // echo $result[1][5]."\r\n";
                        // echo $result[1][7].$result[1][8]."\r\n";
                        // echo $result[1][9]."\r\n";
                        // $rfq_id = trim(str_replace(['\x2d','\x2a'], ['-','*'], $result[1][1]));
                        // echo $rfq_id."\r\n";

                        $rfq = new Rfq;
                        $rfq->rfq_id = $rfq_id = trim(str_replace(['\x2d','\x2a'], ['-','*'], $result[1][1]));
                        $rfq->title = $title;
                        $rfq->desc = $content = trim(strip_tags($this->hextostr($result[1][5])));
                        $rfq->quantity = $quantity = $result[1][8]." ".$this->hextostr($result[1][9]);
                        $rfq->postdate = $result[1][10];
                        $rfq->country = $country = $result[1][6];
                        $rfq->reached = "Reached";
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
                        'mail_to'=>'rfq@hihometech.cn'
                        ];
                        $job = new SendReminderEmail($data);
                        dispatch($job);
                        ++$a;
                    }
                }
                //更新此次查询时间
                $task->updated_at = time();
                $task->save();
            }
        }
        echo $a;
    }
    // 将16进制转义转换为字符
    public function hextostr($hex)
    {
        return preg_replace_callback('/\\\x([0-9a-fA-F]{2})/', function($matches) {
            return chr(hexdec($matches[1]));
        }, $hex);
    }
}
