<x-app-layout>
    <x-slot name="header">
        {{ __('Create category') }}
    </x-slot>

    <div class="rounded-lg bg-white p-4 shadow-md">

        <form action="{{ route('categories.store') }}" method="POST">
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
                <div class="mt-1 inline-flex space-x-1">
                    <input class="text-purple-600 form-checkbox focus:shadow-outline-purple focus:border-purple-400 focus:outline-none"
                           type="checkbox" name="is_visible" id="is_visible"
                            @checked(old('is_visible', true))>
                    <x-input-label for="is_visisble">Visible?</x-input-label>
                </div>
                <x-input-error :messages="$errors->get('is_visible')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-primary-button>
                    {{ __('Submit') }}
                </x-primary-button>
            </div>
        </form>

    </div>
</x-app-layout>
