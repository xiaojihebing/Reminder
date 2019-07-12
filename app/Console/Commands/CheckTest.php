<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpQuery;
use App\Rfq;
use App\Smzdmtask;
use App\Jobs\SendReminderEmail;

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
        //推送到队列
        //$subject = "[" . $task->name . "]" . $price;
        $url = 'https://sourcing.alibaba.com/rfq_search_list.htm?searchText=usb+cable&recently=Y';
        preg_match_all('/data.push\(([\s\S]*?)\)\;/i', file_get_contents($url), $lists);
        
        //推送到队列
        $data = [
        'tmpl'=>'emails.test',
        'subject'=>'This is a subjuect',
        'content'=>'This is content',
        'mail_to'=>'rfq@hihometech.cn'
        ];
        $job = new SendReminderEmail($data);
        dispatch($job);


        foreach($lists[1] as $li){
            // $li = str_replace(['\x2d','\x2a','\x20','\x3c','\x3e','\x2f'], ['-','*','<','>','/'], $li);
            preg_match_all('/:(.*?),\r/i', str_replace('"', '', $li), $result);
            // preg_match('/\d{10}/i', $result[1][11], $rfq_id);
                    
            // echo $title = trim(strip_tags($this->hextostr($result[1][2])));
            // echo $content = trim(strip_tags($this->hextostr($result[1][4])));
            // echo "----";
            // echo $country = $result[1][5];
            // $count = RFQ::all()->count();

            //$rfql = RFQ::where('id','>', $count-500)->where('title', $title)->where('country', $country)->first();
            
            echo $rfq_id = trim(str_replace(['\x2d','\x2a'], ['-','*'], $result[1][1]));
            echo $title = trim(strip_tags($this->hextostr($result[1][2])));
            echo $content = trim(strip_tags($this->hextostr($result[1][4])));
            echo $quantity = $result[1][7]." ".$this->hextostr($result[1][8]);
            echo $result[1][9];
            echo $country = $result[1][5];
            echo "Reached";
            echo "\n";
        }
    }

    public function hextostr($hex)
    {
        return preg_replace_callback('/\\\x([0-9a-fA-F]{2})/', function($matches) {
            return chr(hexdec($matches[1]));
        }, $hex);
    }
}