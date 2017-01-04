<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendReminderEmail;
use phpQuery;
use GuzzleHttp\Client;

class TestController extends Controller
{
    /**
     * 测试队列
     */
    public function index()
    {
    	//待采集的目标页面
        $url = 'https://lantouzi.com/api/bianxianjihua/datalist?page=1&order=0&dir=1&tag=3';
        $client = new Client([
            'base_uri' => 'https://lantouzi.com/api/bianxianjihua/datalist?page=1&order=0&dir=1&tag=3',
            'timeout'  => 3.0,
        ]);
        $response = $client->request('GET', '', [
            'headers' => [
                'User-Agent'=> 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25',
                'Accept'    => 'application/json, text/plain, */*',
                'Referer'   => 'https://lantouzi.com/bianxianjihua/mobile_list?order=0&dir=1&tag=3'
            ]
        ]);

        echo $response->getBody();
        die;


        // phpQuery::newDocumentFile($url);
        
        // //选择要采集的范围
        // $artlist = pq(".list .item");


        //测试数组

    	$data = [
    	'tmpl'=>'emails.test',
    	'subject'=>'this is a new title...', 
    	'content'=>'this is content.', 
    	'mail_to'=>'gongxi@sooga.cn'
    	];
    	$job = new SendReminderEmail($data);//->delay(30);
        dispatch($job);
        return 'ok';
    }

    public function get_fcontent($url,  $timeout = 5)
    {
        $url = str_replace( "&amp;", "&", urldecode(trim($url)));
        // $cookie = tempnam ("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        // 模拟浏览器 在HTTP请求中包含一个"User-Agent: "头的字符串。
        $header = [
            'Referer'=>'https://lantouzi.com/bianxianjihua/mobile_list?order=0&dir=1&tag=3',
            'Accept'=>'application/json, text/plain, */*'
        ];
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A5376e Safari/8536.25" );
        // 需要获取的URL地址，也可以在 curl_init()函数中设置。
        curl_setopt( $ch, CURLOPT_URL, $url);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $header);
        // 连接结束后保存cookie信息的文件。
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // curl_setopt( $ch, CURLOPT_COOKIEJAR, $cookie );
        // 启用时会将服务器服务器返回的"Location: "放在header中递归的返回给服务器，使用CURLOPT_MAXREDIRS可以限定递归返回的数量。
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
        //HTTP请求头中"Accept-Encoding: 的值。支持的编码有"identity"，"deflate"和"gzip"。如果为空字符串""，请求头会发送所有支持的编码类型。
        // curl_setopt( $ch, CURLOPT_ENCODING, "" );
        //将 curl_exec()获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        // 当根据Location:重定向时，自动设置header中的Referer:信息。
        // curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
        //禁用后cURL将终止从服务端进行验证。使用CURLOPT_CAINFO选项设置证书使用CURLOPT_CAPATH选项设置证书目录 如果CURLOPT_SSL_VERIFYPEER(默认值为2)被启用，CURLOPT_SSL_VERIFYHOST需要被设置成TRUE否则设置为FALSE。
        // curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        //在发起连接前等待的时间，如果设置为0，则无限等待。
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        //设置cURL允许执行的最长毫秒数。
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        //指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的。
        // curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
        $content = curl_exec( $ch );
        curl_close ($ch);
        return $content;
    }
}
