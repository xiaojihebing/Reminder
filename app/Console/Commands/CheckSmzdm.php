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
        $tasks = Smzdmtask::where('status', 1)->get();        
        foreach($tasks as $task){
            if ((time()-strtotime($task->updated_at)) > $task->rate) {
                // echo $task->name . "\r\n";
                $a = 0;
                $b = "";
                $url = "https://api.smzdm.com/v1/list?keyword=&type=home&category_id=&brand_id=&mall_id=183&order=time&day=&limit=10&offset=0&f=android&v=360&weixin=1";
                $res= file_get_contents($task->rurl);
                $res = json_decode($res, true);
                //error_code
                if ($res['error_code'] != 0) {
                    //推送到队列
                    $b = $b."查询出错----";
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
                    $b = $b."查询到".count($res['data']['rows'])."个结果----";
                    foreach($res['data']['rows'] as $article){
                        // $m = Smzdm::where('pid', $article['article_id'])->first();
                        // $n = $article['article_channel_id'] < 6;
                        if (!Smzdm::where('pid', $article['article_id'])->first() && $article['article_channel_id'] < 6) {
                            $smzdm = new Smzdm;
                            $smzdm->url = $url = $article['article_url'];
                            $smzdm->pid = $pid = $article['article_id'];
                            $smzdm->title = $title = $article['article_title'];
                            $smzdm->price = $price = $article['article_price'];
                            $smzdm->mall = $mall = $article['article_mall'];
                            $smzdm->pdate = $pdate = $article['article_format_date'];
                            $smzdm->save();

                            $b = $b."New Msg:".$pid."----";

                            //推送到队列
                            $subject = "[" . $task->name . "]" . $price;
                            $data = [
                            'tmpl'=>'emails.smzdm',
                            'pid'=>$pid,
                            'title'=>$title,
                            'subject'=>$subject,
                            'mall'=>$mall,
                            'pdate'=>$pdate,
                            'mail_to'=>'gongxi@sooga.cn'
                            ];
                            $job = new SendReminderEmail($data);
                            dispatch($job);
                            $b = $b."Sent New Msg:".$pid."----";
                            ++$a;
                        } else {
                            continue;
                        }
                    }
                }
                //更新此次查询时间
                $task->result = $b;
                $task->updated_at = time();
                $task->save();
            }
        }
    }
}
