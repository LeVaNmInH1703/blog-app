<?php

namespace App\Events;

use App\Models\GroupChatDetail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HasNewMessageEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $message;
    public function __construct($message)  
    {
        $this->message = $message;
    }
    public function broadcastOn(): array
    {   
        return [
            new PrivateChannel('channelHasNewMessage.'.$this->message->chat_id),
        ];
    }
    public function broadcastWith(): array{
        return [
            'message'=> $this->message,
        ] ;
    }
}
