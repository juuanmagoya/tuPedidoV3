@extends('layouts.app')

@section('title', 'Editar Categoría')

@section('content')
<div class="max-w-3xl space-y-6">

    {{-- =========================================================
    | Header
    |=========================================================
    | Título y descripción de la vista de edición
    |=========================================================
    --}}
    <div>
        <h1 class="text-2xl font-semibold text-white">Editar categoría</h1>
        <p class="text-sm text-gray-400">
            Actualizar información de la categoría
        </p>
    </div>

    {{-- =========================================================
    | CONTENEDOR ALPINE (CRÍTICO)
    |=========================================================
    | ❌ Error que tuvimos inicialmente:
    | - El botón del modal intentaba ejecutar:
    |     $refs.editForm.submit()
    | - Pero el formulario NO estaba dentro de x-data
    | - Resultado: el submit nunca se ejecutaba
    |
    | ✅ Solución correcta:
    | - Envolver el form en un div con x-data
    | - Alpine registra correctamente x-ref
    |=========================================================
    --}}
    <div x-data>

        {{-- =====================================================
        | Formulario de edición
        |=====================================================
        | - Usa método PUT (via @method)
        | - NO se envía automáticamente
        | - Se envía solo tras confirmar en el modal
        |=====================================================
        --}}
        <form
            x-ref="editForm" {{-- Referencia usada por el modal --}}
            method="POST"
            action="{{ route('categories.update', $category) }}"
            enctype="multipart/form-data"
            class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
        >
            {{-- Seguridad --}}
            @csrf
            @method('PUT')

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
                    value="{{ old('name', $category->name) }}"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933]
                           rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B]"
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
                    class="w-full bg-[#0B1220] border border-[#1F2933]
                           rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B]"
                >{{ old('description', $category->description) }}</textarea>
            </div>

            {{-- =================================================
            | Campo: Imagen
            |=================================================
            | - Muestra la imagen actual (si existe)
            | - Permite subir una nueva
            | - La lógica de reemplazo vive en el Service
            |=================================================
            --}}
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-300">
                    Imagen
                </label>

                @if($category->image)
                    <img
                        src="{{ asset('storage/'.$category->image) }}"
                        class="h-24 rounded-lg border border-[#1F2933]"
                    >
                @endif

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

                {{-- Cancelar: navegación normal --}}
                <a
                    href="{{ route('categories.index') }}"
                    class="px-4 py-2 rounded-lg border border-[#1F2933]
                           text-gray-300 hover:bg-[#0B1220]"
                >
                    Cancelar
                </a>

                {{-- =================================================
                | Botón Actualizar (PUNTO CLAVE)
                |=================================================
                | ❌ Error común:
                | - Usar type="submit"
                | - El form se enviaba sin confirmación
                |
                | ❌ Otro error previo:
                | - Intentar pasar action/method al modal
                |
                | ✅ Implementación correcta:
                | - type="button"
                | - Abre modal de confirmación
                | - El modal ejecuta:
                |     $refs.editForm.submit()
                |
                | Resultado:
                | ✔ Confirmación previa
                | ✔ Un solo formulario
                | ✔ PUT correcto
                | ✔ Sin errores 419
                |=================================================
                --}}
                <button
                    type="button"
                    @click="$store.modal.show({
                        title: 'Confirmar actualización',
                        message: '¿Deseás actualizar esta categoría?',
                        onConfirm: () => $refs.editForm.submit()
                    })"
                    class="bg-blue-600 hover:bg-blue-700 px-6 py-2 rounded-lg font-semibold"
                >
                    Actualizar
                </button>

            </div>
        </form>
    </div>
</div>
@endsection
