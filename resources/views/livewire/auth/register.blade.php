<div>
    @if ($view == 'normal')
        <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-6">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <img class="mx-auto h-24 w-auto" src="{{ asset(config('app.icon')) }}" alt="{{ config('app.name') }}">
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">{{ __('Create your Account') }}</h2>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <x-card>
                    <form wire:submit="register" class="flex flex-col gap-6 p-4">
                        <x-input :label="__('Name')" wire:model="user.name" />
                        <x-input :label="__('Email address')" wire:model="user.email" />
                        <x-password :label="__('Password')" wire:model="password" />
                        <x-password :label="__('Confirm Password')" wire:model="password_confirmation" />
                        <section class="flex items-baseline gap-2">
                            <x-checkbox wire:model="terms" id='terms' />
                            <label for='terms'>
                                {{ __('I agree to the') }}
                                <a target="_blank" href="{{ route('terms') }}"class="text-primary-500">{{ __('Terms and Conditions') }}</a>
                            </label>
                        </section>
                        <x-button type="submit" :label="__('Sign up')" primary full lg />
                    </form>
                </x-card>
            </div>
        </div>
    @elseif($view == 'verify-email')
        @include('livewire.auth.verify-email')
    @endif
</div>