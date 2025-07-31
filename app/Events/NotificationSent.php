<?php

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class NotificationSent implements ShouldBroadcast
{
    use InteractsWithSockets;

    public $notification;

    public function __construct($notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications');
    }

    public function broadcastWith()
    {
        return ['notification' => $this->notification];
    }
}
