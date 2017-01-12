<?php

namespace App\Console\Commands;

use App\Jingdong;
use App\Jobs\SendReminderEmail;
use Illuminate\Console\Command;

class CheckJdstock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:jdstock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check the items stock';

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
        // 待查询页面
        $jds = Jingdong::where('type', 1)->where('status', 1)->get();
        foreach ($jds as $jd) {
            if ((time()-strtotime($jd->updated_at)) > $jd->rate) {
                echo $skuids = $jd->skuid;
                $area = "19_1607_47388_0"; //深圳龙华区
                $url = 'https://c0.3.cn/stocks?type=batchstocks&skuIds=' . $skuids . '&area=' . $area;
                $res= file_get_contents($url);
                $res = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($res));
                $res = json_decode($res, true);

                if ($res[$skuids]['StockState'] != '34') {
                    //推送到队列
                    $subject = "[" . $jd->name . "]到货啦~";

                    $data = [
                    'tmpl'=>'emails.jdstock',
                    'name'=>$jd->name,
                    'skuid'=>$jd->skuid,
                    'subject'=>$subject,
                    'mail_to'=>'gongxi@sooga.cn'
                    ];

                    $job = new SendReminderEmail($data);
                    dispatch($job);
                    // echo "sucess";
                    
                    // 改变status，停止查货
                    $jingdong = new Jingdong;
                    $jingdong->status = 0;
                    $jingdong->save();
                }

                // 更新时间
                $jd->updated_at = time();
                $jd->save();
            }
        }
    }
}