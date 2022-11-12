<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Coderflex\LaravelTicket\Models\Message;
use Illuminate\Notifications\Messages\MailMessage;

class CommentEmailNotification extends Notification
{
    public function __construct(protected Message $message)
    {}

    public function via($notifiable): array
    {
        if (config('app.enable_notifications')) {
            return ['mail'];
        }

        return [];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New comment on ticket ' . $this->message->ticket->title)
            ->line('New comment on ticket '.$this->message->ticket->title . ':')
            ->line($this->message->message)
            ->action('View full ticket', route('tickets.show', $this->message->ticket))
            ->line('Thank you!');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
