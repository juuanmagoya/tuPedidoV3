<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans">

<div class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md bg-white p-6 rounded shadow">

        <h1 class="text-2xl font-bold mb-6 text-center">Iniciar sesión</h1>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">
                    Email
                </label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="mt-1 block w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">
                    Password
                </label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="mt-1 block w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                >
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me --}}
            <div class="flex items-center mb-4">
                <input
                    id="remember_me"
                    name="remember"
                    type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                >
                <label for="remember_me" class="ml-2 text-sm text-gray-600">
                    Recordarme
                </label>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between">
                @if (Route::has('password.request'))
                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm text-indigo-600 hover:underline"
                    >
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif

                <button
                    type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700"
                >
                    Ingresar
                </button>
            </div>
        </form>

    </div>
</div>

</body>
</html>
