<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen flex">

    {{-- Sidebar + Topbar --}}
    @include('layouts.navigation')

    {{-- Main Content --}}
    <div class="flex-1 sm:ml-64 flex flex-col">

        {{-- Page Header --}}
        @hasSection('header')
            <header class="bg-white shadow h-16 flex items-center px-6">
                @yield('header')
            </header>
        @endif

        {{-- Page Content --}}
        <main class="pt-16 sm:pt-0">
            @yield('content')
        </main>

    </div>
</div>
</body>
</html>
