<x-app-layout>
    <x-slot name="header">
        {{ __('Create user') }}
    </x-slot>

    <div class="rounded-lg bg-white p-4 shadow-md">

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input type="text"
                              id="name"
                              name="name"
                              class="block w-full"
                              value="{{ old('name') }}"
                              required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input type="email"
                              id="email"
                              name="email"
                              class="block w-full"
                              value="{{ old('email') }}"
                              required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input type="password"
                              id="password"
                              name="password"
                              class="block w-full"
                              value="{{ old('password') }}"
                              required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mt-4">
                <div class="space-y-2">
                    <x-input-label for="role" :value="__('Role')" />
                    <div class="space-x-2">
                        @foreach($roles as $id => $name)
                            <div class="inline-flex space-x-1">
                                <input class="text-purple-600 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple" type="radio" name="role" id="role-{{ $id }}" value="{{ $id }}" @checked($id == old('role') || $name == 'agent')>
                                <label class="text-sm text-gray-700" for="role-{{ $id }}">{{ $name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('role')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-4">
                <x-primary-button>
                    {{ __('Submit') }}
                </x-primary-button>
            </div>
        </form>

    </div>
</x-app-layout>
