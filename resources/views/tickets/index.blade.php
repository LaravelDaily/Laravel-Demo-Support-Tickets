<x-app-layout>
    <x-slot name="header">
        {{ __('Tickets') }}
    </x-slot>

    <div class="mb-4 flex justify-between">
        <a class="rounded-lg border border-transparent bg-purple-600 px-4 py-2 text-center text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:outline-none focus:ring active:bg-purple-600" href="{{ route('tickets.create') }}">
            {{ __('Create') }}
        </a>
        <div class="flex space-x-2">
            <select class="block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50" name="status" id="status" onChange="window.location.href=this.value">
                <option value="{{ clearQueryString('status') }}">-- SELECT STATUS --</option>
                @foreach(\Coderflex\LaravelTicket\Enums\Status::cases() as $status)
                    <option value="{{ toggle('status', $status->value) }}" @selected($status->value == request('status'))>{{ $status->name }}</option>
                @endforeach
            </select>

            <select class="block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50" name="priority" id="priority" onchange="window.location.href=this.value">
                <option value="{{ clearQueryString('priority') }}">-- SELECT PRIORITY --</option>
                @foreach(\Coderflex\LaravelTicket\Enums\Priority::cases() as $priority)
                    <option value="{{ toggle('priority', $priority->value) }}" @selected($priority->value == request('priority'))>{{ $priority->name }}</option>
                @endforeach
            </select>

            <select class="block w-full rounded-md border-gray-300 shadow-sm focus-within:text-primary-600 focus:border-primary-300 focus:ring-primary-200 focus:ring focus:ring-opacity-50" name="category" id="category" onchange="window.location.href=this.value">
                <option value="{{ clearQueryString('category') }}">-- SELECT CATEGORY --</option>
                    @foreach(\App\Models\Category::pluck('name', 'id') as $id => $name)
                        <option value="{{ toggle('category', (string) $id) }}" @selected($id == request('category'))>{{ $name }}</option>
                    @endforeach
            </select>
        </div>
    </div>

    <div class="rounded-lg bg-white p-4 shadow-xs">

        <div class="mb-8 w-full overflow-hidden rounded-lg border shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th class="px-4 py-3">Title</th>
                            <th class="px-4 py-3">Author</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Priority</th>
                            <th class="px-4 py-3">Categories</th>
                            <th class="px-4 py-3">Labels</th>
                            @hasanyrole('admin|agent')
                                <th class="px-4 py-3">Assigned to</th>
                            @endhasanyrole
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y">
                        @forelse($tickets as $ticket)
                            <tr class="text-gray-700">
                                <td class="px-4 py-3 text-sm">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="hover:underline">{{ $ticket->title }}</a>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $ticket->user->name }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $ticket->status }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    {{ $ticket->priority }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @foreach($ticket->categories as $category)
                                        <span class="rounded-full bg-gray-50 px-2 py-2">{{ $category->name }}</span>
                                    @endforeach
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @foreach($ticket->labels as $label)
                                        <span class="rounded-full bg-gray-50 px-2 py-2">{{ $label->name }}</span>
                                    @endforeach
                                </td>
                                @hasanyrole('admin|agent')
                                    <td class="px-4 py-3 text-sm">
                                        {{ $ticket->assignedToUser->name ?? '' }}
                                    </td>
                                @endhasanyrole
                                <td class="px-4 py-3 space-x-2">
                                    @hasanyrole('admin|agent')
                                        <a class="rounded-lg border-2 border-transparent bg-purple-600 px-4 py-2 text-center text-sm font-medium leading-5 text-white transition-colors duration-150 hover:bg-purple-700 focus:outline-none focus:ring active:bg-purple-600" href="{{ route('tickets.edit', $ticket) }}">
                                            {{ __('Edit') }}
                                        </a>
                                    @endhasanyrole

                                    @role('admin')
                                        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" onsubmit="return confirm('Are you sure?')" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <x-primary-button>
                                                Delete
                                            </x-primary-button>
                                        </form>
                                    @endrole
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-4 py-3" colspan="4">No tickets found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tickets->hasPages())
                <div class="border-t bg-gray-50 px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 sm:grid-cols-9">
                    {{ $tickets->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
