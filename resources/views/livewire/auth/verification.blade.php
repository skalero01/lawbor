<div>
    @if (!$valid)
        <div class="flex flex-col justify-center py-12 min-h-full sm:px-6 lg:px-6">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <img class="mx-auto w-auto h-24" src="{{ asset(config('app.icon')) }}" alt="{{ config('app.name') }}">
                <h2 class="mt-6 text-3xl font-bold tracking-tight text-center text-gray-900">
                    {{ __('Verify Your Email Address') }}
                </h2>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
                <x-card>
                    <form wire:submit="request" class="flex flex-col gap-6 p-4">
                        <p class="text-center">
                            {{ __('Before proceeding, please check your email for a verification link.') }}
                            {{ __('If you did not receive the email') }},

                            <button type="submit" wire:loading.attr="disabled" class="inline p-0 m-0 align-baseline text-primary-500">{{ __('click here to request another') }}</button>
                        </p>
                    </form>
                </x-card>
            </div>
        </div>
    @else
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <x-card>
                <p class="italic text-center">{{ __('Verified!') }}</p>
            </x-card>
        </div>
    @endif
</div>