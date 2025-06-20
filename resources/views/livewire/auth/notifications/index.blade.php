<div>
    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 dark:text-gray-100 sm:truncate sm:text-3xl sm:tracking-tight">{{ __('Notification Center') }}</h2>
        </div>
    </div>

    <div class="overflow-x-auto -mx-4 mt-6 shadow ring-1 ring-black ring-opacity-5 dark:ring-white/10 sm:-mx-6 md:mx-0 md:rounded-lg">
        @forelse ($notifications as $notification)
            <a href="{{ $notification->data['url'] ?? '#' }}" target="{{ $notification->data['url'] ?? '_self' }}" class="flex px-4 py-3 border-b {{ $notification->is_read ? 'bg-white dark:bg-gray-800' : 'bg-amber-100 dark:bg-amber-700/40' }} hover:bg-gray-50 dark:hover:bg-gray-700 dark:border-gray-700">
                <div class="flex-shrink-0">
                    <img class="rounded-full w-11 h-11" src="{{ $notification->data['image'] ?? asset(config('app.icon')) }}" alt="Avatar">
                    <div class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 border border-white rounded-full bg-primary-600 dark:border-gray-800 dark:bg-primary-700">
                            @if(str_contains($notification->data['icon'], 'fa'))
                                <i class="{{ $notification->data['icon'] }} w-3 h-3 text-white"></i>
                            @else
                                <x-icon class="w-3 h-3 text-white" name="{{ $notification->data['icon'] ?? 'bell' }}" />
                            @endif
                    </div>
                </div>
                <div class="w-full pl-3">
                    <div class="text-gray-500 font-normal text-sm mb-1.5 dark:text-gray-400">
                        <p>{{ $notification->data['title'] ?? '' }}</p>
                        <small>
                            {!! $notification->data['description'] ?? '' !!}
                        </small>
                    </div>
                    <div class="text-xs font-medium text-primary-700 dark:text-primary-400" title="@userDate($notification->created_at)">
                        {{ $notification->created_at->diffForHumans() }}
                    </div>
                </div>
            </a>
        @empty
            <div class="py-20 mt-4 text-center text-gray-500 bg-white dark:bg-gray-800 dark:text-gray-400 ring-1 ring-black ring-opacity-5 dark:ring-white/10 md:rounded-lg">
                {{ __('No data to show') }}
            </div>
        @endforelse
        <div class="sticky bottom-0 right-0 items-center w-full p-4 bg-white border-t border-gray-200 sm:flex sm:justify-between dark:bg-gray-800 dark:border-gray-700">
            {!! $notifications->links() !!}
        </div>
        
    </div>
    
</div>
