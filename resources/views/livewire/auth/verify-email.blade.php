<div class="flex flex-col justify-center py-12 min-h-full sm:px-6 lg:px-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <img class="mx-auto w-auto h-24" src="{{ asset(config('app.icon')) }}" alt="{{ config('app.name') }}">
        <h2 class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900">{{ __('Please confirm your email') }}</h2>
        @if (config('auth.enable_register'))
            <p class="mt-2 text-sm text-center text-gray-600">
                <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">
                    {{ __('A code was sent to your email address') }}
                </a>
            </p>
        @endif
    </div>
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl">
        <x-card>
            <form wire:submit="validateCode" class="flex flex-col gap-6 p-4">
                <x-input :label="__('Validation Code')" wire:model="user_code" />
                <x-button type="submit" :label="__('Confirm Email')" primary full lg />
            </form>
        </x-card>
    </div>
</div>