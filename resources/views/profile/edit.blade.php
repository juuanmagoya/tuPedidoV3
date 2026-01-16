<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

<!-- NAVBAR / SIDEBAR -->
@include('layouts.navigation')

<!-- CONTENIDO -->
<div class="pt-16 sm:ml-64 px-6 py-8">

    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            Profile
        </h2>
    </div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- UPDATE PROFILE -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- UPDATE PASSWORD -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- DELETE USER -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>

    </div>
</div>

</body>
</html>
