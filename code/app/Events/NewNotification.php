<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    // Here you create the message to be sent when the event is triggered.
    public function __construct(User $user) {
        $this->user = $user;
    }
    
    // You should specify the name of the channel created in Pusher.
    public function broadcastOn() {
        return 'lbaw2374';
    }

    // You should specify the name of the generated notification.
    public function broadcastAs() {
        $name = $this->user->id . '-newnotification';
        return $name;
    }
}
