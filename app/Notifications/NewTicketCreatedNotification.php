<?php

namespace App\Notifications;

use App\Models\Ticket;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewTicketCreatedNotification extends Notification
{
    public function __construct(protected Ticket $ticket) {}

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
            ->subject('New ticket')
            ->line('New ticket have been created: ' . $this->ticket->title)
            ->action('View ticket', route('tickets.show', $this->ticket))
            ->line('Thank you!');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
