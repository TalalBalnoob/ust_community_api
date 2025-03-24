<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewMediaNotification extends Notification
{
    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Supports mail, database, broadcast, slack, etc.
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'New media content has been uploaded!',
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'New media content has been uploaded!',
        ]);
    }
}
