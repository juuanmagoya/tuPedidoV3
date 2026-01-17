@extends('layouts.app')

@section('title', 'Nuevo Producto')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-white">Nuevo Producto</h1>
        <p class="text-gray-400">
            Crear un nuevo producto para el sistema
        </p>
    </div>

    <!-- Contenedor Alpine para el modal -->
    <div x-data>

        <!-- Formulario -->
        <form
            x-ref="form"
            method="POST"
            action="{{ route('products.store') }}"
            enctype="multipart/form-data"
            class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
        >

            @csrf

            <!-- Incluimos el form parcial con todos los campos -->
            @include('products._form')

        </form>

    </div>

</div>
@endsection
