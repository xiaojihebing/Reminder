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
        $title = "data cable";
        $rfql = Rfq::orderBy('id', 'DESC')->where('title', $title)->first();
        echo $rfql->id;
    }

    public function hextostr($hex)
    {
        return preg_replace_callback('/\\\x([0-9a-fA-F]{2})/', function($matches) {
            return chr(hexdec($matches[1]));
        }, $hex);
    }
}