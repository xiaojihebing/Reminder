<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Rfq;
use App\Rfqtask;
use phpQuery;

class CheckTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $url = 'https://sourcing.alibaba.com/rfq_search_list.htm?searchText=usb+cable'; //.str_replace(' ','+',$task->keyword).'&recently=Y';
        // $res= file_get_contents($url);
        preg_match_all('/data.push\(([\s\S]*?)\)\;/i', file_get_contents($url), $lists);
        $i = 1;

        // print_r($lists);die;

        foreach($lists[1] as $li){

            preg_match_all('/:(.*?),\r/i', str_replace(["\"", "decodeEntities", "(", ")"],"", $li), $rfq);
            // print_r($rfq[1]);die;
            // echo substr(str_replace('\x2', '', $rfq[1][1]), 0, 24) . "\r\n";
            $rfq_id = trim(str_replace(['\x2d','\x2a'], ['-','*'], $rfq[1][1]));
            echo $rfq_id."\r\n";
            // str_replace("\x2","",$rfq[1][1]);
            // if (preg_match('/\d{10}/i', $rfq[1][1], $rfq_id)) {
            //     echo $i."\r\n";
            //     echo $rfq_id[0]."\r\n";
            //     echo "http:".trim($rfq[1][0])."\r\n";
            //     echo trim(strip_tags($rfq[1][1]))."\r\n";
            //     echo trim(strip_tags($this->hextostr($rfq[1][3])))."\r\n";
            //     echo $rfq[1][4]."\r\n";
            //     echo $rfq[1][6].$rfq[1][7]."\r\n";

            //     echo "\r\n";
            //     $i++;
            // } else {
            //     echo "error";
            // }
            
            // echo "-------------------------------------------------------------------------"."\r\n";
            // echo $rfq[1][0]."\r\n";
            // echo $rfq[1][1]."\r\n";
            // echo $rfq[1][3]."\r\n";
            // echo $rfq[1][4]."\r\n";
            // echo $rfq[1][6]."\r\n";
            // echo $rfq[1][7]."\r\n";
            // echo $rfq[1][11]."\r\n";

            // if ($m && !$n) {
            //     $rfq = new Rfq;
            //     $rfq->rfq_id = $rfq_id = $result[0];;
            //     $rfq->title = $title = trim(pq($li)->find('.item-title a')->text());
            //     $rfq->desc = $content = trim(pq($li)->find('.item-digest')->text());
            //     $rfq->quantity = $quantity = pq(pq($li)->find('.item-other-count span'))->attr('title');

            //     $temp = pq(pq($li)->find('.item-info'))->attr('title');
            //     preg_match('/\d{4}-\d{1,2}-\d{1,2}/i',$temp,$postdate);
            //     $rfq->postdate = $postdate[0];

            //     $rfq->country = $country = pq(pq($li)->find('.country-flag'))->attr('title');
            //     $rfq->reached = pq($li)->find('.item-action-left span')->text();
            //     $rfq->related = $task->keyword;
            //     $rfq->save();
            //     // echo $result[0] ."\r\n";

            //     //推送到队列
            //     $subject = "[" . $quantity . "]" . $title;

            //     $data = [
            //     'tmpl'=>'emails.remindrfq',
            //     'rfq_id'=>$rfq_id,
            //     'subject'=>$subject,
            //     'content'=>$content,
            //     'country'=>$country,
            //     'mail_to'=>'colin@3gxun.com'
            //     ];
            //     $job = new SendReminderEmail($data);
            //     dispatch($job);
            //     ++$a;
            // } else {
            //     continue;
            // }
        }
    }

    public function hextostr($hex)
    {
        return preg_replace_callback('/\\\x([0-9a-fA-F]{2})/', function($matches) {
            return chr(hexdec($matches[1]));
        }, $hex);
    }
}