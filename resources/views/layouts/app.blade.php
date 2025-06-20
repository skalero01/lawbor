@extends('layouts.base')
@push('styles')
    @include('layouts.components.styles')
    <script src="{{asset('build/assets/main.js')}}"></script>
@endpush
@push('scripts')
    <script src="{{asset('build/assets/sticky.js')}}"></script>
    @vite('resources/assets/js/custom-switcher.js')
@endpush
@section('content-base')


    <!-- LOADER -->
    <div id="loader" >
        <img src="{{asset('build/assets/images/media/loader.svg')}}" alt="">
    </div>
    <!-- LOADER -->
    
    <div class="page">
                
        <!-- HEADER -->
        @include('layouts.components.header')

        <!-- END HEADER -->

        <!-- SIDEBAR -->
        @includeIf('layouts.sidebars.' . (Request::segment(1) ?? 'app'))

        <!-- END SIDEBAR -->

        <!-- MAIN-CONTENT -->
        <div class="main-content app-content">
            <div class="container-fluid">
                <!-- Page Header -->
                @if(isset($breadcrumb) || isset($title))
                <div class="flex items-center justify-between page-header-breadcrumb flex-wrap gap-2">
                    <div>
                        @isset ($breadcrumb)
                            @include('layouts.components.breadcrumbs')
                        @endisset
                        @isset ($title)
                            <h1 class="page-title font-medium text-lg mb-0">{{ $title }}</h1>
                        @endisset
                    </div>
                </div>
                @endif
                <!-- Page Header Close -->

                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </div>
        <!-- MAIN-CONTENT -->

        <!-- FOOTER -->
        @include('layouts.components.footer')

        <!-- END FOOTER -->
    </div>

    <!-- SCRIPTS -->
    @include('layouts.components.scripts')

@endsection
