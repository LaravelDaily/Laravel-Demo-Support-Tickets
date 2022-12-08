<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Requests\TicketRequest;
use Coderflex\LaravelTicket\Models\Label;
use Illuminate\Database\Eloquent\Builder;
use Coderflex\LaravelTicket\Models\Category;
use App\Notifications\AssignedTicketNotification;
use App\Notifications\NewTicketCreatedNotification;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $tickets = Ticket::with('user', 'categories', 'labels', 'assignedToUser')
            ->when($request->has('status'), function (Builder $query) use ($request) {
                return $query->where('status', $request->input('status'));
            })
            ->when($request->has('priority'), function (Builder $query) use ($request) {
                return $query->withPriority($request->input('priority'));
            })
            ->when($request->has('category'), function (Builder $query) use ($request) {
                return $query->whereRelation('categories', 'id', $request->input('category'));
            })
            ->when(auth()->user()->hasRole('agent'), function (Builder $query) {
                $query->where('assigned_to', auth()->user()->id);
            })
            ->when(auth()->user()->hasRole('user'), function (Builder $query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->latest()
            ->paginate();

        return view('tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        $labels = Label::visible()->pluck('name', 'id');

        $categories = Category::visible()->pluck('name', 'id');

        $users = User::role('agent')->orderBy('name')->pluck('name', 'id');

        return view('tickets.create', compact('labels', 'categories', 'users'));
    }

    public function store(TicketRequest $request)
    {
        $ticket = auth()->user()->tickets()->create($request->only('title', 'message', 'status', 'priority'));

        $ticket->attachCategories($request->input('categories'));

        $ticket->attachLabels($request->input('labels'));

        if ($request->input('assigned_to')) {
            $ticket->assignTo($request->input('assigned_to'));
            User::find($request->input('assigned_to'))->notify(new AssignedTicketNotification($ticket));
        } else {
            User::role('admin')
                ->each(fn ($user) => $user->notify(new NewTicketCreatedNotification($ticket)));
        }

        if (!is_null($request->input('attachments'))) {
            foreach ($request->input('attachments') as $file) {
                $ticket->addMediaFromDisk($file, 'public')->toMediaCollection('tickets_attachments');
            }
        }

        return to_route('tickets.index');
    }

    public function show(Ticket $ticket): View
    {
        $this->authorize('view', $ticket);

        $ticket->load(['media', 'messages' => fn ($query) => $query->latest()]);

        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket): View
    {
        $this->authorize('update', $ticket);

        $labels = Label::visible()->pluck('name', 'id');

        $categories = Category::visible()->pluck('name', 'id');

        $users = User::role('agent')->orderBy('name')->pluck('name', 'id');

        return view('tickets.edit', compact('ticket', 'labels', 'categories', 'users'));
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->update($request->only('title', 'message', 'status', 'priority', 'assigned_to'));

        $ticket->syncCategories($request->input('categories'));

        $ticket->syncLabels($request->input('labels'));

        if ($ticket->wasChanged('assigned_to')) {
            User::find($request->input('assigned_to'))->notify(new AssignedTicketNotification($ticket));
        }

        if (!is_null($request->input('attachments')[0])) {
            foreach ($request->input('attachments') as $file) {
                $ticket->addMediaFromDisk($file, 'public')->toMediaCollection('tickets_attachments');
            }
        }

        return to_route('tickets.index');
    }

    public function destroy(Ticket $ticket)
    {
        $this->authorize('delete', $ticket);

        $ticket->delete();

        return to_route('tickets.index');
    }

    public function upload(Request $request)
    {
        $path = [];

        if ($request->file('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tmp', 'public');
            }
        }

        return $path;
    }

    public function close(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->close();

        return to_route('tickets.show', $ticket);
    }

    public function reopen(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->reopen();

        return to_route('tickets.show', $ticket);
    }

    public function archive(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->archive();

        return to_route('tickets.show', $ticket);
    }
}
