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

class CommentEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    // Here you create the message to be sent when the event is triggered.
    public function __construct($message) {
        $this->message = $message;
    }
    
    // You should specify the name of the channel created in Pusher.
    public function broadcastOn() {
        return 'lbaw2374';
    }

    // You should specify the name of the generated notification.
    public function broadcastAs() {
        $name = Auth::user()->id . '-notification';
        return $name;
    }
}

