<div class="flex flex-col justify-center py-12 min-h-full sm:px-6 lg:px-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto w-auto h-24" src="{{ asset(config('app.icon')) }}" alt="{{ config('app.name') }}">
        <h2 class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900">
            {{ __('Confirm Password') }}
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <x-card>
            <form wire:submit="confirm" class="flex flex-col gap-6 p-4">
                <x-password :label="__('Password')" wire:model="password" />
                <x-button type="submit" :label="__('Confirm')" primary full lg />
            </form>
        </x-card>
    </div>
</div>
