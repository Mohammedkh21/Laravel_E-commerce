<?php

namespace App\Http\Controllers\Notification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public  function MakeNotificationAsReaded(Request $request){
        $Notification = DatabaseNotification::find($request->notification_id);
        $Notification->markAsRead();
    }
}
