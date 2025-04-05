<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class LikePostNotification extends Notification
{
    public function __construct(public string $username, public string $post_id) {}

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Supports mail, database, broadcast, slack, etc.
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->username . " Liked your post",
            'ar_message' => $this->username . " اعجب بنشورك ",
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
