<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Coderflex\LaravelTicket\Models\Ticket;
use Illuminate\Notifications\Messages\MailMessage;

class AssignedTicketNotification extends Notification
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
            ->subject('You have been assigned a new ticket')
            ->line('You have been assigned a new ticket: ' . $this->ticket->title)
            ->action('View ticket', route('tickets.show', $this->ticket))
            ->line('Thank you!');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
