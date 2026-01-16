<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Password</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md bg-white p-6 rounded-lg shadow">
    <p class="text-sm text-gray-600 mb-4">
        This is a secure area. Please confirm your password.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

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

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
                Confirm
            </button>
        </div>
    </form>
</div>

</body>
</html>
