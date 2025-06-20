<div>
    @if ($view == 'normal')
        <div class="flex flex-col justify-center py-12 min-h-full sm:px-6 lg:px-6">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <img class="mx-auto w-auto h-24" src="{{ asset(config('app.icon')) }}" alt="{{ config('app.name') }}">
                <h2 class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900">
                    {{ __('Sign in to your account') }}
                </h2>
                @if (config('auth.enable_register') && config('auth.login_steps') != 'two')
                    <p class="mt-2 text-sm text-center text-gray-600">
                        <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500">
                            {{ __('Or create new account') }}
                        </a>
                    </p>
                @endif
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <x-card>
                    @if (config('auth.login_steps') == 'two')
                        @if ($step == 1)
                            <form wire:submit="loginTwoSteps" class="flex flex-col gap-6 p-4">
                                <x-input :label="__('Email address')" wire:model="email" />
                                <x-button type="submit" :label="__('Sign in')" primary full lg />
                            </form>
                        @elseif($step == 2)
                            <form wire:submit="loginTwoSteps" class="flex flex-col gap-6 p-4">
                                <x-password :label="__('Password')" wire:model="password" />
                                <div class="flex justify-between items-center">
                                    <x-checkbox :label="__('Remember me')" wire:model="remember" />
                                    <x-button :label="__('Forgot Your Password?')" :href="route('password.request')" primary flat />
                                </div>
                                <x-button type="submit" :label="__('Sign in')" primary full lg />
                            </form>
                        @elseif($step == 3)
                            <form wire:submit="loginTwoSteps" class="flex flex-col gap-6 p-4">
                                <x-password :label="__('New Password')" wire:model="password" />
                                <x-button type="submit" :label="__('Create password')" primary full lg />
                            </form>
                        @endif
                    @else
                        <form wire:submit="login" class="flex flex-col gap-6 p-4">
                            <x-input :label="__('Email address')" wire:model="email" />
                            <x-password :label="__('Password')" wire:model="password" />
                            <div class="flex justify-between items-center">
                                <x-checkbox :label="__('Remember me')" wire:model="remember" />
                                <x-button :label="__('Forgot Your Password?')" :href="route('password.request')" primary flat />
                            </div>
                            <x-button type="submit" :label="__('Sign in')" primary full lg />
                        </form>
                    @endif
                </x-card>
            </div>
        </div>
    @elseif($view == 'verify-email')
        @include('livewire.auth.verify-email')
    @endif
</div>
