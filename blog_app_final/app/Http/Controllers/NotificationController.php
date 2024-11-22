<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotification(Notification $notification){
        if(!$notification||!Auth::user()->notifications->contains($notification)) return;
        return view(
            'components.notification-item-component',
            [
                'content' => $notification->content,
                'imageSrc' => $notification->user->avatar_filename,
                'imageName' => __('public.Avatar'),
                'linkToOpen' => $notification->link,
                'time'=>$notification->created_at->diffForHumans(),
                'keyWord'=>$notification->key_word
            ]
        )->render();
    }
}
