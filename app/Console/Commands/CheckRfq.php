<?php

namespace App\Console\Commands;

// use App\Article;
// use App\Task;
use phpQuery;
use Illuminate\Console\Command;

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
        $url = 'https://sourcing.alibaba.com/rfq_search_list.htm?searchText=usb+cable&recently=Y';
        phpQuery::newDocumentFile($url);

        //选择要采集的范围
        $artlist = pq(".list .item");
        $a = 0;
        foreach($artlist as $li){
            $temp = pq(pq($li)->find('.rfq-btn'))->attr('url');
            // echo $temp;
            $m = preg_match('/\d{10}/i',$temp,$result);
            echo $result[0] . "\r\n";
            sleep(3);
            ++$a;
        }
        return $a;
    }
}
