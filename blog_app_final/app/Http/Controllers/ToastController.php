<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToastController extends Controller
{
    public function getForMessage(Message $message)
    {
        if (!$message || !$message->group->users->contains('id', Auth::user()->id)) abort(400);
        return str(view('components.notification-message-item-component', [
            'content' => $message->content,
            'imageSrc' => $message->user->avatar_filename,
            'time' => $message->created_at,
            'title' => $message->user->name,
            'groupId'=>$message->group->id
        ])->render());
    }
}
