<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use phpQuery;
use App\Smzdm;
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
        $url = "https://api.smzdm.com/v1/list?keyword=&type=faxian&category_id=&brand_id=3419&mall_id=&order=time&day=&limit=10&offset=0&f=android&v=360&weixin=1";
        $res= file_get_contents($url);
        $res = json_decode($res, true);
        //error_code
        if ($res['error_code'] != 0) {
            //推送到队列
            $data = [
            'tmpl'=>'emails.error',
            'subject'=>'smzdm查询出错啦',
            'content'=>$task->name,
            'mail_to'=>'gongxi@sooga.cn'
            ];
            $job = new SendReminderEmail($data);
            dispatch($job);
        } else {
            echo count($res['data']['rows']) . "\r\n";
            foreach($res['data']['rows'] as $article){
                // $m = Smzdm::where('pid', $article['article_id'])->first();
                // $n = $article['article_channel_id'] < 6;
                if (!Smzdm::where('pid', $article['article_id'])->first() && $article['article_channel_id'] < 6) {
                    echo $article['article_id']."\r\n";
                    // $smzdm = new Smzdm;
                    // $smzdm->url = $url = $article['article_url'];
                    // $smzdm->pid = $pid = $article['article_id'];
                    // $smzdm->title = $title = $article['article_title'];
                    // $smzdm->price = $price = $article['article_price'];
                    // $smzdm->mall = $mall = $article['article_mall'];
                    // $smzdm->pdate = $pdate = $article['article_format_date'];
                    // $smzdm->save();

                    //推送到队列
                    // $subject = "[" . $task->name . "]" . $price;
                    // $data = [
                    // 'tmpl'=>'emails.smzdm',
                    // 'pid'=>$pid,
                    // 'title'=>$title,
                    // 'subject'=>$subject,
                    // 'mall'=>$mall,
                    // 'pdate'=>$pdate,
                    // 'mail_to'=>'gongxi@sooga.cn'
                    // ];
                    // $job = new SendReminderEmail($data);
                    // dispatch($job);
                    // ++$a;
                } else {
                    continue;
                }
            }
        }
        //更新此次查询时间
        // $task->updated_at = time();
        // $task->save();
    }

    public function hextostr($hex)
    {
        return preg_replace_callback('/\\\x([0-9a-fA-F]{2})/', function($matches) {
            return chr(hexdec($matches[1]));
        }, $hex);
    }
}