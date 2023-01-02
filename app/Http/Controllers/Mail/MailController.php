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
        $this->dispatch(new MailJob($request->mail_type,$request->email_text));

        return response()->json(['status'=>true]);
    }

}
