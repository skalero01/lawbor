<li class="header-element notifications-dropdown !hidden xl:!block hs-dropdown ti-dropdown [--auto-close:inside]">

    <!-- Start::header-link|dropdown-toggle -->
    <a href="javascript:void(0);" class="header-link hs-dropdown-toggle ti-dropdown-toggle"
        data-bs-toggle="dropdown" data-bs-auto-close="outside" id="messageDropdown" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 header-link-icon" fill="none"
            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
        </svg>
        @if ($totalNotification > 0)
            <span class="header-icon-pulse bg-primarytint2color rounded pulse pulse-secondary"></span>
        @endif
    </a>
    <!-- End::header-link|dropdown-toggle -->

    <!-- Start::main-header-dropdown -->
    <div class="main-header-dropdown hs-dropdown-menu ti-dropdown-menu hidden" data-popper-placement="none">
        <div class="p-4">
            <div class="flex items-center justify-between">
                <p class="mb-0 text-[15px] font-medium">{{ __('Notifications') }}</p>
                <span class="badge bg-secondary text-white rounded-sm" id="notifiation-data">{{ $totalNotification }} {{ __('Unread') }}</span>
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <ul class="list-none mb-0" id="header-notification-scroll">
            @foreach ($notifications as $notification)
            <li class="ti-dropdown-item block">
                <div class="flex items-center">
                    <div class="pe-2 leading-none">
                        <img class="rounded-full w-11 h-11" src="{{ $notification->data['image'] ?? asset(config('app.icon')) }}" alt="Image">
                        <div class="absolute flex items-center justify-center w-5 h-5 ml-6 -mt-5 border border-white rounded-full {{ $notification->data['color'] ?? 'bg-primary-700' }} dark:border-gray-700">
                            @if(str_contains($notification->data['icon'], 'fa'))
                                <i class="{{ $notification->data['icon'] }}" class="w-3 h-3 text-white"></i>
                            @else
                                <x-icon class="w-3 h-3 text-white" name="{{ $notification->data['icon'] ?? 'bell' }}" />
                            @endif
                        </div>
                    </div>
                    <div class="grow flex items-center justify-between">
                        <div>
                            <p class="mb-0 font-medium">
                                <a href="{{ $notification->data['url'] ?? '#' }}" target="{{ $notification->data['url'] ?? '_self' }}" wire:click.defer="markAsRead('{{ $notification->getKey() }}')" >
                                    {{ $notification->data['title'] ?? '' }}
                                </a>
                            </p>
                            <div class="text-textmuted dark:text-textmuted/50 font-normal text-xs header-notification-text truncate">
                                {!! $notification->data['description'] ?? '' !!}
                            </div>
                            <div class="font-normal text-[10px] text-textmuted dark:text-textmuted/50 op-8">
                                {{ $notification->created_at->diffForHumans() }}
                            </div>
                        </div>
        
                    </div>
                </div>
            </li>
            @endforeach
        </ul>


        <div class="p-[3rem] empty-item1 @if ($totalNotification > 0) hidden @endif">
            <div class="text-center">
                <span class="avatar avatar-xl avatar-rounded bg-secondary/10 !text-secondary">
                    <i class="fas fa-bell-slash fs-2"></i>
                </span>
                <h6 class="font-medium mt-3">{{ __('No New Notifications') }}</h6>
            </div>
        </div>
        <div class="p-4 empty-header-item1 border-t">
            <div class="grid">
                <a href="{{ route('auth.notifications.center') }}" class="ti-btn ti-btn-primary btn-wave">{{ __('View All') }}</a>
            </div>
        </div>
    </div>
    <!-- End::main-header-dropdown -->
</li>