@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-white">
            Dashboard
        </h1>
        <p class="text-sm text-gray-400">
            Resumen general del sistema
        </p>
    </div>

    <!-- Card -->
    <div class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 shadow-lg">
        <h2 class="text-lg font-semibold text-white">
            Hola 👋
        </h2>
        <p class="text-gray-400 text-sm">
            Este es tu dashboard
        </p>
    </div>

</div>
@endsection
