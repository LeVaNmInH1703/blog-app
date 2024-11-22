@extends('layout.container')
@section('title', 'Home page')
@section('content-container')
    @include('layout.header')
    @yield('style-app')
    @yield('style-blog-partial-view')
    @yield('content-app')
    @yield('script-app')
    @include('layout.footer')
    
@endsection
