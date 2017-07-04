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
        $data = [
        'tmpl'=>'emails.smzdm',
        'pid'=>'43435435',
        'title'=>'this is Title',
        'subject'=>'This is subject',
        'mall'=>'This is Mall',
        'pdate'=>'This is Pdate',
        'mail_to'=>'gongxi@sooga.cn'
        ];
        $job = new SendReminderEmail($data);
        dispatch($job);
        echo "success";
    }

    public function hextostr($hex)
    {
        return preg_replace_callback('/\\\x([0-9a-fA-F]{2})/', function($matches) {
            return chr(hexdec($matches[1]));
        }, $hex);
    }
}