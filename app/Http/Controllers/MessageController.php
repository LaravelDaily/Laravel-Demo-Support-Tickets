<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Http\Requests\MessageRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use App\Notifications\CommentEmailNotification;

class MessageController extends Controller
{
    public function store(MessageRequest $request, Ticket $ticket): RedirectResponse
    {
        $message = $ticket->messages()->create($request->validated() + ['user_id' => auth()->user()->id]);

        $users = $ticket->messages()
            ->pluck('user_id')
            ->push($ticket->user_id)
            ->filter(fn ($user) => $user != auth()->user()->id)
            ->unique();

        Notification::send(User::findMany($users), new CommentEmailNotification($message));

        return to_route('tickets.show', $ticket);
    }
}
