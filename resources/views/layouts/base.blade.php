<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical" class="light" data-header-styles="light" data-menu-styles="dark" data-width="fullwidth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', config('app.name')) </title>
        <link rel="shortcut icon" href="{{ asset(config('app.icon')) }}" />
        <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.0/css/all.min.css" onload="this.rel='stylesheet'">
        @yield('head')
        @wireUiScripts
        @vite(['resources/sass/app.scss'])
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')
        @laravelPWA
    </head>
    <body class="@yield('body-classes', 'bg-gray-100')">
        <x-notifications />
        <x-dialog />
        @yield('content-base')
        @livewireScripts
        @filepondScripts
        @stack('scripts')
        @yield('footer')
        @livewire('wire-elements-modal')
        @include('layouts.components.wireui-notifications')
    </body>
</html>
