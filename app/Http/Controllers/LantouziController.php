<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendReminderEmail;
use phpQuery;
use GuzzleHttp\Client;
use App\Lantouzi;

class LantouziController extends Controller
{
    //
    public function index()
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
        	// echo $key . "----" . $value['id'] . "----" . $value['title'] . "----" . $value['buy_url'] . "<br>";
        	$m = $value['rate'];
        	$n = Lantouzi::where('lid', $value['id'])->first();
        	if ($m > 10 && !$n){
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

        		//推送到队列
                $subject = "[" . $value['rate'] . "%]" . $value['title'];

                $data = [
    			'tmpl'=>'emails.lantouzi',
    			'subject'=>$subject,
    			'days'=>$value['days'],
    			'money'=>$value['format_money'] - $value['sold_amount']/100,
    			'buy_url'=>$value['buy_url'], 
    			'mail_to'=>'gongxi@sooga.cn'
    			];
    			$job = new SendReminderEmail($data);//->delay(30);
        		dispatch($job);
        	}

        }
        return $a;
    }
}
