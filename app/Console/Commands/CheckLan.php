<?php

namespace App\Console\Commands;

use phpQuery;
use GuzzleHttp\Client;
use App\Lantouzi;
use Mail;
use Illuminate\Console\Command;

class CheckLan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:lan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check for lantouzi';

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
        //待采集的目标页面
        // $url = 'https://lantouzi.com/api/bianxianjihua/datalist?page=1&order=0&dir=1&tag=3';
        $client = new Client([
            'base_uri' => 'https://lantouzi.com/api/bianxianjihua/datalist?page=1&order=0&dir=1&tag=0',
            'timeout'  => 3.0,
        ]);
        $response = $client->request('GET', '', [
            'headers' => [
                'User-Agent'=> 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25',
                'Accept'    => 'application/json, text/plain, */*',
                'Referer'   => 'https://lantouzi.com/bianxianjihua/mobile_list?order=0&dir=1&tag=0'
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        $a = 0;
        foreach ($result['data']['items'] as $key => $value) {
            $m = $value['rate'];
            $n = Lantouzi::where('lid', $value['id'])->first();
            if ($m > 10 && !$n){
                echo $value['id'] . "----";
                $lantouzi = new Lantouzi;
                $lantouzi->lid = $value['id'];
                $lantouzi->title = $value['title'];
                $lantouzi->rate = $value['rate'];
                $lantouzi->days = $value['days'];
                $lantouzi->remain_money = $value['format_money'] - $value['sold_amount']/100;
                $lantouzi->buy_url = $value['buy_url'];
                $lantouzi->status = $value['status'];
                $lantouzi->save();
                ++$a;

                // 发送邮件
                $subject = "[" . $value['rate'] . "%]" . $value['title'];
                $data = [
                'tmpl'=>'emails.lantouzi',
                'subject'=>$subject,
                'days'=>$value['days'],
                'money'=>$value['format_money'] - $value['sold_amount']/100,
                'buy_url'=>$value['buy_url'], 
                'mail_to'=>'gongxi@sooga.cn'
                ];
                $this->SendEmail($data);
            }
        }
        echo $a;
    }

    /**
     * 发送邮件函数
     */
    function SendEmail($data)
    {
        // 发送邮件
        Mail::send($data['tmpl'], $data, function($message) use($data) {
            $message->from('16655376@qq.com', 'Reminder');
            $message->subject($data['subject']);
            $message->to($data['mail_to']);
        });
        sleep(5);
        return true;
    }
}
