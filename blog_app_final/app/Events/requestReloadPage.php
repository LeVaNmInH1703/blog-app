<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class requestReloadPage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $channelName;
    public $user_id;
    /**
     * Create a new event instance.
     */
    public function __construct($channelName, $user_id = null)
    {
        $this->channelName = $channelName;
        $this->user_id = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        // dd($this->channelName.'.'.$this->user_id);
        if ($this->user_id)
            return [
                new PrivateChannel($this->channelName . '.' . $this->user_id),
                // new Channel($this->channelName.'.'.$this->user_id),
            ];
        else
            return [
                new Channel($this->channelName),
            ];
    }
}
