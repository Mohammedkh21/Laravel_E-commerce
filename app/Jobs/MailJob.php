<?php

namespace App\Jobs;

use App\Mail\SendMailTo;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\User;
use App\Models\VistorMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $mail_type ;
    public   $text = '';
    public function __construct($mail_type,$text)
    {
        $this->mail_type = $mail_type;
        $this->text = $text;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $emails =[];
        switch ($this->mail_type){
            case 'all':
                array_push($emails,User::all()->pluck('email'));
                array_push($emails,Admin::all()->pluck('email'));
                array_push($emails,Seller::all()->pluck('email'));
                array_push($emails,VistorMail::all()->pluck('email'));
                break;
            case 'users':
                array_push($emails,User::all()->pluck('email'));
                break;
            case 'admins':
                array_push($emails,Admin::all()->pluck('email'));
                break;
            case 'sellers':
                array_push($emails,Seller::all()->pluck('email'));
                break;
            case 'visitors':
                array_push($emails,VistorMail::all()->pluck('email'));
                break;
        }

        foreach ($emails as $type){
            Mail::to($type)->send(new SendMailTo($this->text));
        }
    }
}
