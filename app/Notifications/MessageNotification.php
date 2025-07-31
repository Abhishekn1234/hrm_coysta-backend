<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MessageNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    protected $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // both DB + real-time
    }

    public function toDatabase($notifiable)
    {
        return [
            'message_id' => $this->message->id,
            'subject' => $this->message->subject,
            'content' => $this->message->content,
            'priority' => $this->message->priority,
            'sent_at' => now()->toDateTimeString(),
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message_id' => $this->message->id,
            'subject' => $this->message->subject,
            'content' => $this->message->content,
            'priority' => $this->message->priority,
            'sent_at' => now()->toDateTimeString(),
        ]);
    }

    public function broadcastOn()
    {
        return ['App.Models.User.' . $this->message->user_id];
    }
}
