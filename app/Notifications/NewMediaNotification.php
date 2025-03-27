<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewFollowNotification extends Notification
{
    public function __construct(public string $username)
    {
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Supports mail, database, broadcast, slack, etc.
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->username . " follow your account",
            'ar_message' => $this->username . "بداء بمتابتك ",
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'New media content has been uploaded!',
        ]);
    }
}
