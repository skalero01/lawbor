<div>
    @include('layouts.components.loading')
    <div class="grid grid-cols-1 xl:grid-cols-3 xl:gap-4 dark:bg-gray-900">
        <div class="mb-4 col-span-full xl:mb-2">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ __('Manage profile') }}</h1>
        </div>
        <!-- Right Content -->
        <div class="col-span-full xl:col-auto">
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
                    <img class="mb-4 rounded-lg w-28 h-28 sm:mb-0 xl:mb-4 2xl:mb-0" src="{{ auth()->user()?->avatar }}" alt="{{ auth()->user()->name }}">
                    <div>
                        <h3 class="mb-1 text-xl font-bold text-gray-900 dark:text-white">{{ __('Profile picture') }}</h3>
                        <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('JPG, GIF or PNG. Max size of 800K') }}
                        </div>
                        <x-input type="file" wire:model.live="avatar" class="hidden" id="file" />
                        <div class="flex items-center space-x-4 mb-2">
                            <button type="button" onclick="document.getElementById('file').click();" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-600 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                <svg class="w-4 h-4 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1.977A4.5 4.5 0 1113.5 13H11V9.413l1.293 1.293a1 1 0 001.414-1.414l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13H5.5z"></path><path d="M9 13h2v5a1 1 0 11-2 0v-5z"></path></svg>
                                {{ __('Change picture')}}
                            </button>
                        </div>
                        @error('avatar') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <form wire:submit="updateLocaleAndTimezone">
                    <h3 class="mb-4 text-xl font-semibold dark:text-white">{{ __("Language and time") }}</h3>
                    <div class="mb-4">
                        <x-select wire:model="locale" :label="__('Select language')" :placeholder="__('Select language')">
                            @foreach (config('app.languages') as $key => $item)
                                <x-select.option :label="__($item)" :value="$key" />
                            @endforeach
                        </x-select>
                    </div>
                    <div class="mb-4">
                        <x-select wire:model="timezone" :label="__('Select Time zone')" :placeholder="__('Select Time zone')" :options="\DateTimeZone::listIdentifiers()" />
                    </div>
                    <div class="col-span-6 sm:col-full">
                        <x-button type="submit" :label="__('Save')" primary md />
                    </div>
                </form>
            </div>
        </div>
        <div class="col-span-2">
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <h3 class="mb-4 text-xl font-semibold dark:text-white">{{ __('General information') }}</h3>
                <form wire:submit="updateGeneralInformation">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <x-input :label="__('Name')" wire:model="user.name" :placeholder="__('Name')"/>
                        </div>
                        <div class="col-span-6 sm:col-span-3">
                            <x-input :label="__('E-Mail Address')" wire:model="user.email" type="email" :placeholder="__('E-Mail Address')"/>
                        </div>
                        <div class="col-span-6 sm:col-full">
                            <x-button type="submit" :label="__('Save')" primary md />
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                <h3 class="mb-4 text-xl font-semibold dark:text-white">{{ __('Password information') }}</h3>
                <form wire:submit="changePassword" class="flex flex-col gap-6">
                    <x-password :label="__('New password')" wire:model="password.new" />
                    <x-password :label="__('Confirm password')" wire:model="password.new_confirmation" />
                    <div class="col-span-6 sm:col-full">
                        <x-button type="submit" :label="__('Save')" primary md />
                    </div>
                </form>
            </div>

            <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800 xl:mb-0">
                <div class="flow-root">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        <!-- Item 1 -->
                        <div class="flex items-center justify-between py-4">
                            <div class="flex flex-col flex-grow">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ __("Email notification") }}</div>
                                <div class="text-base font-normal text-gray-500 dark:text-gray-400">{{ __("Notify me by email of all actions on my account") }}</div>
                            </div>
                            <label for="rating-reminders" class="relative flex items-center cursor-pointer">
                                <x-toggle wire:model="notifications.email" name="toggle" lg />
                            </label>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button wire:click="updateNotifications" class="text-white bg-primary-500 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
