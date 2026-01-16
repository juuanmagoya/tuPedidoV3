@extends('layouts.app')

@section('title', 'Nueva Categoría')

@section('content')
<div class="max-w-3xl space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-white">Nueva categoría</h1>
        <p class="text-sm text-gray-400">
            Crear una nueva categoría
        </p>
    </div>

    <!-- Form -->
<div x-data>
    <form
        x-ref="form"
        method="POST"
        action="{{ route('categories.store') }}"
        enctype="multipart/form-data"
        class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
    >


        @csrf

        <!-- Name -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">
                Nombre
            </label>
            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-[#F59E0B] focus:outline-none"
                   required>
        </div>

        <!-- Description -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">
                Descripción
            </label>
            <textarea name="description"
                      rows="4"
                      class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white focus:ring-2 focus:ring-[#F59E0B] focus:outline-none">{{ old('description') }}</textarea>
        </div>

        <!-- Image -->
        <div>
            <label class="block text-sm font-medium text-gray-300 mb-1">
                Imagen
            </label>
            <input type="file"
                   name="image"
                   class="block w-full text-sm text-gray-400">
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('categories.index') }}"
               class="px-4 py-2 rounded-lg border border-[#1F2933] text-gray-300 hover:bg-[#0B1220] transition">
                Cancelar
            </a>

        <button
            type="button"
            @click="$store.modal.show({
                title: 'Confirmar creación',
                message: '¿Deseás crear esta categoría?',
                onConfirm: () => $refs.form.submit()
            })"
            class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-6 py-2 rounded-lg font-semibold"
        >
            Guardar
        </button>



        </div>

    </form>
</div>

</div>
@endsection
