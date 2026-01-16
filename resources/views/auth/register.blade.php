<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white p-6 rounded-lg shadow">
    <h1 class="text-2xl font-bold mb-6 text-center">Register</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">
                Name
            </label>
            <input id="name" type="text" name="name"
                   value="{{ old('name') }}"
                   required autofocus
                   class="mt-1 block w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

            @error('name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email
            </label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   required
                   class="mt-1 block w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">
                Password
            </label>
            <input id="password" type="password" name="password"
                   required
                   class="mt-1 block w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                Confirm Password
            </label>
            <input id="password_confirmation" type="password"
                   name="password_confirmation"
                   required
                   class="mt-1 block w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:underline">
                Already registered?
            </a>

            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Register
            </button>
        </div>
    </form>
</div>

</body>
</html>
