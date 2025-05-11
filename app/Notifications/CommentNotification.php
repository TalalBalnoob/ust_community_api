<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class CommentNotification extends Notification
{
    public function __construct(public string $username, public string $post_id) {}

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Supports mail, database, broadcast, slack, etc.
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'comment',
            'username' => $this->username,
            'user_id' => $notifiable->id . '',
            'post_id' => $this->post_id
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'New media content has been uploaded!',
        ]);
    }
}
