<?php

namespace App\Console\Commands;

use App\Jingdong;
use App\Jobs\SendReminderEmail;
use Illuminate\Console\Command;

class CheckJd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:jd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check the items stock and price';

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
        $jds = Jingdong::where('status', 1)->get();
        foreach ($jds as $jd) {
            if ((time()-strtotime($jd->updated_at)) > $jd->rate) {
                switch ($jd->type) {
                    case 0:
                        $url = 'https://pe.3.cn/prices/mgets?origin=5&area=19_1607_47388_0.137662574&pdtk=&pduid=1454506579&pdpin=12032058-31006879&pdbp=0&skuIds=' . $jd->skuid;
                        $res= file_get_contents($url);
                        // echo gettype($res);die;
                        // $res = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($res));
                        // echo $res;die;
                        $res = json_decode($res, true);
                        echo $res[0]['p'];

                        if ($res[0]['p'] < $jd->target_price) {
                            //构造数组
                            $subject = "[" . $jd->name . "]降价啦~";

                            $data = [
                            'tmpl'=>'emails.jdstock',
                            'skuid'=>'' . $jd->skuid,
                            'subject'=>$subject,
                            'mail_to'=>'gongxi@sooga.cn'
                            ];
                            $job = new SendReminderEmail($data);
                            dispatch($job);// echo "sucess";
                            
                            // 改变status，停止查询
                            $jd->status = 0;
                            $jd->save();
                        } else {
                            // 更新时间
                            $jd->updated_at = time();
                            $jd->save();
                        }
                        break;

                    case 1:
                        $area = "19_1607_47388_0"; //深圳龙华区
                        $url = 'https://c0.3.cn/stocks?type=batchstocks&skuIds=' . $jd->skuid . '&area=' . $area;
                        $res= file_get_contents($url);
                        $res = iconv('UTF-8', 'UTF-8//IGNORE', utf8_encode($res));
                        $res = json_decode($res, true);
                        echo $res[$jd->skuid]['StockState'];

                        if ($res[$jd->skuid]['StockState'] != '34') {
                            //构造数组
                            $subject = "[" . $jd->name . "]到货啦~";

                            $data = [
                            'tmpl'=>'emails.jdstock',
                            'skuid'=>'' . $jd->skuid,
                            'subject'=>$subject,
                            'mail_to'=>'gongxi@sooga.cn'
                            ];
                            // $job = new SendReminderEmail($data);
                            dispatch(new SendReminderEmail($data));// echo "sucess";
                            
                            // 改变status，停止查询
                            $jd->status = 0;
                            $jd->save();
                        } else {
                            // 更新时间
                            $jd->updated_at = time();
                            $jd->save();
                        }
                        break;
                    
                    default:
                        echo "this is default";
                        break;
                }
            }
        }
    }
}