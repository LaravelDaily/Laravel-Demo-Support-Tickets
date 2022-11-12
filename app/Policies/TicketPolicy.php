<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('agent') && $ticket->assigned_to === $user->id) {
            return true;
        }

        if ($ticket->user_id === $user->id) {
            return true;
        }

        return false;
    }

    public function update(User $user, Ticket $ticket): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        if ($user->hasRole('agent') && $ticket->assigned_to === $user->id) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole('admin');
    }
}