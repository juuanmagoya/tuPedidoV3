<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white p-6 rounded-lg shadow">
    <h1 class="text-xl font-bold mb-4 text-center">Forgot Password</h1>

    <p class="text-sm text-gray-600 mb-4">
        Forgot your password? Enter your email and we’ll send you a reset link.
    </p>

    @if (session('status'))
        <p class="mb-4 text-sm text-green-600">
            {{ session('status') }}
        </p>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">
                Email
            </label>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}"
                   required autofocus
                   class="mt-1 block w-full rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">

            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
            Email Password Reset Link
        </button>
    </form>
</div>

</body>
</html>
