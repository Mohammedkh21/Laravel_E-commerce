<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use App\Jobs\MailJob;
use App\Mail\SendMailTo;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\User;
use App\Models\VistorMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function StoreMail(Request $request){
        $request->validate(['email'=>'required|unique:vsitor_mails,email']);
        VistorMail::create(['email'=>$request->email]);
        return response()->json(['status'=>true]);
    }

    public function SendEmail(Request $request){
        $request->validate([
            'mail_type'=>'required|in:all,users,admins,sellers,visitors',
            'email_text'=>'required'
        ]);
        $emails =[];
        switch ($request->mail_type){
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

        $this->dispatch(new MailJob($emails,$request->email_text));
//        $this->dispatch((new MailJob($emails,$request->email_text))->onQueue('redis'));

        return response()->json(['status'=>true,'data'=>$emails]);
    }

}
