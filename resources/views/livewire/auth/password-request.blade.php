<div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto h-24 w-auto" src="{{ asset(config('app.icon')) }}" alt="{{ config('app.name') }}">
        <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
            @lang('Change your password')
        </h2>
        <p class="mt-4 text-center text-gray-600">
            @lang('You\'ve been requested to change your password')
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <x-card>
            <form wire:submit="handle" class="flex flex-col gap-6 p-4">
                <input class="sr-only" type="email" name="email" value="{{ auth()->user()->email }}" />
                @if ($this->getConfirmedProperty())
                    <x-password :label="__('New Password')" wire:model="password" autocomplete="new-password" />
                    <x-password :label="__('Confirm Password')" wire:model="password_confirmation" autocomplete="new-password" />
                @else
                    <x-password :label="__('Current password')" wire:model="current_password" />
                @endif
                <x-button type="submit" :label="$this->getConfirmedProperty() ? __('Confirm') : __('Continue')" primary full lg />
            </form>
        </x-card>
    </div>
</div>
