<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RefreshDevice implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $deviceCode;
    /**
     * Create a new event instance.
     */
    public function __construct($code)
    {
        $this->deviceCode=$code;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('refresh-device-' . $this->deviceCode),

        ];
    }
}
