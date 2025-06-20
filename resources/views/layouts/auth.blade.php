@extends('layouts.base')
@section('content-base')
    {{ $slot ?? '' }}
    @yield('content')
@endsection
