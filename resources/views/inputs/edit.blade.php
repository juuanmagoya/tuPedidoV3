@extends('layouts.app')

@section('title', 'Editar Insumo')

@section('content')
<div class="max-w-4xl mx-auto px-4 space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-white">Editar Insumo</h1>
        <p class="text-gray-400">Modificar datos del insumo</p>
    </div>

    <form
        x-ref="form"
        method="POST"
        action="{{ route('inputs.update', $input) }}"
        class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
    >
        @csrf
        @method('PUT')

        @include('inputs._form')
    </form>

</div>
@endsection
