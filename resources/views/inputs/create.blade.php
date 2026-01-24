@extends('layouts.app')

@section('title', 'Nuevo Insumo')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-white">Nuevo Insumo</h1>
        <p class="text-gray-400">Registrar un nuevo insumo</p>
    </div>

    <form
        x-ref="form"
        method="POST"
        action="{{ route('inputs.store') }}"
        class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
    >
        @include('inputs._form')
    </form>

</div>
@endsection
