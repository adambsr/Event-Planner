<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AAB Event Planner')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    @if(!request()->routeIs('login') && !request()->routeIs('register'))
        @include('layouts.header')
    @endif

    <main>
        @yield('content')
    </main>

    @if(!request()->routeIs('login') && !request()->routeIs('register'))
        @include('layouts.footer')
    @endif

    @stack('scripts')
</body>
</html>
