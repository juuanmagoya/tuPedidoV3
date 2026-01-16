@extends('layouts.app')

@section('title', 'Nueva Categoría')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- =========================================================
    | Header
    |=========================================================
    | Título y descripción de la vista
    |=========================================================
    --}}
    <div>
        <h1 class="text-2xl font-semibold text-white">Nueva categoría</h1>
        <p class="text-sm text-gray-400">
            Crear una nueva categoría
        </p>
    </div>

    {{-- =========================================================
    | IMPORTANTE: Contenedor Alpine
    |=========================================================
    | Este div con x-data es FUNDAMENTAL.
    |
    | ❌ Error que tuvimos:
    | - El botón intentaba acceder a $refs.form
    | - Pero el form NO estaba dentro de un scope Alpine
    | - Resultado: $refs era undefined → no se enviaba el formulario
    |
    | ✅ Solución:
    | - Envolver el form dentro de un contenedor x-data
    | - Así Alpine puede registrar correctamente x-ref
    |=========================================================
    --}}
    <div x-data>

        {{-- =====================================================
        | Formulario de creación
        |=====================================================
        | - NO se envía directamente con submit
        | - Se envía SOLO después de confirmar en el modal
        |=====================================================
        --}}
        <form
            x-ref="form" {{-- Referencia usada por Alpine para hacer submit --}}
            method="POST"
            action="{{ route('categories.store') }}"
            enctype="multipart/form-data"
            class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
        >

            {{-- Token CSRF obligatorio --}}
            @csrf

            {{-- =================================================
            | Campo: Nombre
            |=================================================
            --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">
                    Nombre
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B] focus:outline-none"
                >
            </div>

            {{-- =================================================
            | Campo: Descripción
            |=================================================
            --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">
                    Descripción
                </label>

                <textarea
                    name="description"
                    rows="4"
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B] focus:outline-none"
                >{{ old('description') }}</textarea>
            </div>

            {{-- =================================================
            | Campo: Imagen
            |=================================================
            --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">
                    Imagen
                </label>

                <input
                    type="file"
                    name="image"
                    class="block w-full text-sm text-gray-400"
                >
            </div>

            {{-- =================================================
            | Acciones
            |=================================================
            --}}
            <div class="flex justify-end gap-3">

                {{-- Botón cancelar: navegación normal --}}
                <a
                    href="{{ route('categories.index') }}"
                    class="px-4 py-2 rounded-lg border border-[#1F2933] text-gray-300
                           hover:bg-[#0B1220] transition"
                >
                    Cancelar
                </a>

                {{-- =================================================
                | Botón Guardar (PUNTO CRÍTICO)
                |=================================================
                | ❌ Error común:
                | - type="submit"
                | - El formulario se enviaba ANTES del modal
                |
                | ❌ Otro error que tuvimos:
                | - Intentar enviar el form desde el modal sin x-ref
                |
                | ✅ Solución correcta:
                | - type="button" → NO envía el form
                | - Abre el modal de confirmación
                | - El modal ejecuta $refs.form.submit()
                |
                | Esto garantiza:
                | ✔ Confirmación del usuario
                | ✔ Un solo form
                | ✔ CSRF intacto
                | ✔ Sin errores 419
                |=================================================
                --}}
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
