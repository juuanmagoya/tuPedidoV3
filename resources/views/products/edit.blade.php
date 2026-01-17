@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
<div class="max-w-4xl mx-auto px-4 space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-white">Editar Producto</h1>
        <p class="text-gray-400">Modifica los datos del producto</p>
    </div>

    <!-- Formulario -->
    <form
        x-ref="form"
        method="POST"
        action="{{ route('products.update', $product) }}"
        enctype="multipart/form-data"
        class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
    >
        @csrf
        @method('PUT')

        @include('products._form') <!-- todos los inputs -->
    </form>


</div>
@endsection
