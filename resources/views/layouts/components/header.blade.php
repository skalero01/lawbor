
<header class="app-header sticky" id="header">

    <!-- Start::main-header-container -->
    <div class="main-header-container container-fluid">

        <!-- Start::header-content-left -->
        <div class="header-content-left">

            <!-- Start::header-element -->
            <div class="header-element">
                <div class="horizontal-logo">
                    <a href="{{route('admin.dashboard')}}" class="header-logo">
                        <img src="{{asset('build/assets/images/brand-logos/desktop-logo.png')}}" alt="logo" class="desktop-logo">
                        <img src="{{asset('build/assets/images/brand-logos/toggle-dark.png')}}" alt="logo" class="toggle-dark">
                        <img src="{{asset('build/assets/images/brand-logos/desktop-dark.png')}}" alt="logo" class="desktop-dark">
                        <img src="{{asset('build/assets/images/brand-logos/toggle-logo.png')}}" alt="logo" class="toggle-logo h-10">
                        <img src="{{asset('build/assets/images/brand-logos/toggle-white.png')}}" alt="logo" class="toggle-white">
                        <img src="{{asset('build/assets/images/brand-logos/desktop-white.png')}}" alt="logo" class="desktop-white">
                    </a>
                </div>
            </div>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <div class="header-element mx-lg-0">
                <a aria-label="Hide Sidebar"
                    class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle"
                    data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
            </div>
            <!-- End::header-element -->

        </div>
        <!-- End::header-content-left -->

        <!-- Start::header-content-right -->
        <ul class="header-content-right">

            <!-- Start::header-element -->
            <li class="header-element md:!hidden block">
                <a href="javascript:void(0);" class="header-link" data-bs-toggle="modal"
                    data-hs-overlay="#header-responsive-search">
                    <!-- Start::header-link-icon -->
                    <i class="fas fa-search header-link-icon"></i>
                    <!-- End::header-link-icon -->
                </a>
            </li>
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <!-- light and dark theme -->
            <li class="header-element header-theme-mode hidden !items-center sm:block md:!px-[0.5rem] px-2">
                <a aria-label="anchor"
                    class="hs-dark-mode-active:hidden flex hs-dark-mode group flex-shrink-0 justify-center items-center gap-2  rounded-full font-medium transition-all text-xs dark:bg-bgdark dark:hover:bg-black/20 text-textmuted dark:text-textmuted/50 dark:hover:text-white dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
                    href="javascript:void(0);" data-hs-theme-click-value="dark">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 header-link-icon" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </a>
                <a aria-label="anchor"
                    class="hs-dark-mode-active:flex hidden hs-dark-mode group flex-shrink-0 justify-center items-center gap-2  rounded-full font-medium text-defaulttextcolor  transition-all text-xs dark:bg-bodybg dark:bg-bgdark dark:hover:bg-black/20  dark:hover:text-white dark:focus:ring-white/10 dark:focus:ring-offset-white/10"
                    href="javascript:void(0);" data-hs-theme-click-value="light">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 header-link-icon" fill="none"
                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                </a>
            </li>
            <!-- End light and dark theme -->
            <!-- End::header-element -->

            <!-- Start::header-element -->
            @livewire('auth.notifications.notification')
            <!-- End::header-element -->

            <!-- Start::header-element -->
            <li class="header-element header-fullscreen">
                <!-- Start::header-link -->
                <a onclick="openFullscreen();" href="javascript:void(0);" class="header-link">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 full-screen-open header-link-icon"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                    </svg>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 full-screen-close header-link-icon hidden"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 9V4.5M9 9H4.5M9 9 3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5 5.25 5.25" />
                    </svg>
                </a>
                <!-- End::header-link -->
            </li>
            <!-- End::header-element -->

            @if(auth()->check())
                <!-- Start::header-element -->
                <li class="header-element ti-dropdown hs-dropdown">
                    <!-- Start::header-link|dropdown-toggle -->
                    <a href="javascript:void(0);" class="header-link hs-dropdown-toggle ti-dropdown-toggle"
                        id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <div class="flex items-center">
                            <div>
                                <img class="w-8 h-8 rounded-full" src="{{ auth()->user()?->avatar }}" alt="{{ __('Profile image') }}">
                            </div>
                        </div>
                    </a>
                    <!-- End::header-link|dropdown-toggle -->
                    <ul class="main-header-dropdown hs-dropdown-menu ti-dropdown-menu pt-0 overflow-hidden header-profile-dropdown hidden"
                        aria-labelledby="mainHeaderProfile">
                        <li>
                            <div
                                class="ti-dropdown-item text-center border-b border-defaultborder dark:border-defaultborder/10 block">
                                <span>
                                    {{ auth()->user()?->name }}
                                </span>
                                <span class="block text-xs text-textmuted dark:text-textmuted/50">{{ auth()->user()->email }}</span>
                            </div>
                        </li>
                        @role('admin')
                            @if (Request::segment(1) != 'admin')
                                <li>
                                    <a class="ti-dropdown-item flex items-center" href="/admin">
                                        <i class="fas fa-user-tie p-1 rounded-full bg-primary/10 text-primary me-2 text-[1rem]"></i>
                                        {{ __('Go To Admin Panel') }}
                                    </a>
                                </li>
                            @endif
                        @endcan
                        @if (Request::segment(1) != 'app')
                            <li>
                                <a class="ti-dropdown-item flex items-center" href="{{ route('app.dashboard') }}">
                                    <i class="fas fa-tachometer-alt p-1 rounded-full bg-primary/10 text-primary me-2 text-[1rem]"></i>
                                    {{ __('Go To User Panel') }}
                                </a>
                            </li>
                        @endif
                        @if (Request::segment(1) != 'auth')
                            <li>
                                <a class="ti-dropdown-item flex items-center" href="{{ route('auth.profile') }}">
                                    <i class="fas fa-user-circle p-1 rounded-full bg-primary/10 text-primary me-2 text-[1rem]"></i>
                                    {{ __('My Profile') }}
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->isActing())
                            <li>
                                <a class="ti-dropdown-item flex items-center" href="{{ route('stop-acting') }}">
                                    <i class="fas fa-lock p-1 rounded-full bg-primary/10 text-primary ut me-2 text-[1rem]"></i>
                                    {{ __('Stop acting') }}
                                </a>
                            </li>
                        @else
                            <li>
                                <a class="ti-dropdown-item flex items-center" href="{{ route('logout') }}">
                                    <i class="fas fa-lock p-1 rounded-full bg-primary/10 text-primary ut me-2 text-[1rem]"></i>
                                    {{ __('Sign out') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
                <!-- End::header-element -->
            @endif

        </ul>
        <!-- End::header-content-right -->

    </div>
    <!-- End::main-header-container -->

</header>
