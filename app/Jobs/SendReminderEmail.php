<?php

namespace App\Jobs;

// use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendReminderEmail implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;
    protected $data;

    // protected $rfq_id;
    // protected $title;
    // protected $content;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        // $this->rfq_id = $rfq_id;
        // $this->title = $title;
        // $this->content = $content;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 发送邮件
        // $data = ['title'=>$this->title, 'content'=>$this->content, 'rfq_id'=>$this->rfq_id];
        $data = $this->data;
        Mail::send($data['tmpl'], $data, function ($message) use($data){
            $message->from('16655376@qq.com', 'Reminder');
            $message->subject($data['subject']);
            $message->to($data['mail_to']);
        });
        Mail::getSwiftMailer()->getTransport()->stop();
        sleep(3);
    }
}