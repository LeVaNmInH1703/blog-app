<?php

namespace App\Events;

use Barryvdh\Debugbar\Twig\Extension\Debug;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class HasNewNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;
    public function __construct($notification)
    {
        $this->notification = $notification;
    }
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channelHasNewNotification.' . $this->notification->user_id_receive),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'notificationId' => $this->notification->id,
        ];
    }
}
