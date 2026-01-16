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

<body 
    x-data 
    class="font-sans antialiased bg-gray-100"
>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('modal', {
        open: false,
        title: '',
        message: '',
        onConfirm: null,

        show({ title, message, onConfirm }) {
            this.title = title
            this.message = message
            this.onConfirm = onConfirm
            this.open = true
        },

        confirm() {
            if (typeof this.onConfirm === 'function') {
                this.onConfirm()
            }
            this.close()
        },

        close() {
            this.open = false
            this.title = ''
            this.message = ''
            this.onConfirm = null
        }
    })
})
</script>



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
        <main class="flex-1 bg-[#08202b] px-6 pt-8">
            @if (session('success'))
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="mb-6"
                >
                    <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-400"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M5 13l4 4L19 7" />
                        </svg>

                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-500/10 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif


            @yield('content')
        </main>

    </div>
</div>
    {{-- 🔥 CONFIRM MODAL GLOBAL --}}
    @include('partials.confirm-modal')
</body>
</html>
