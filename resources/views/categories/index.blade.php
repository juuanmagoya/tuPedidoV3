@extends('layouts.app')

@section('title', 'Categorías')

@section('content')
<div class="space-y-10">

    <!-- Header -->
    <div class="flex items-center justify-between mt-10">
        <div>
            <h1 class="text-2xl font-semibold text-white">Categorías</h1>
            <p class="text-sm text-gray-400">
                Gestión de categorías del sistema
            </p>
        </div>

        <a href="{{ route('categories.create') }}"
           class="inline-flex items-center  gap-2 bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
            + Nueva categoría
        </a>
    </div>

    <!-- Table -->
    <div class="bg-[#111827] border border-[#1F2933] rounded-2xl overflow-hidden shadow-lg">

        <table class="w-full text-sm">
            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Imagen</th>
                    <th class="px-6 py-4 text-left">Nombre</th>
                    <th class="px-6 py-4 text-left">Descripción</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">
                @forelse($categories as $category)
                    <tr class="hover:bg-[#0B1220] transition">
                        <td class="px-6 py-4">
                            @if($category->image)
                                <img src="{{ asset('storage/'.$category->image) }}"
                                     class="h-12 w-12 object-cover rounded-lg">
                            @else
                                <div class="h-12 w-12 bg-gray-700 rounded-lg flex items-center justify-center text-gray-400">
                                    —
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-white font-medium">
                            {{ $category->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            {{ Str::limit($category->description, 50) }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">

                                <!-- Editar -->
                                <a
                                    href="{{ route('categories.edit', $category) }}"
                                    class="flex items-center gap-1 text-[#F59E0B] hover:text-[#FBBF24] text-sm transition"
                                    title="Editar"
                                >
                                    <!-- Pencil Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="w-4 h-4"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.125 19.588 3 21l1.412-4.125L16.862 3.487z"/>
                                    </svg>
                                    Editar
                                </a>

                                <!-- Eliminar -->
                                <div x-data>
                                    <form
                                        x-ref="deleteForm{{ $category->id }}"
                                        action="{{ route('categories.destroy', $category) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            @click="$store.modal.show({
                                                title: 'Eliminar categoría',
                                                message: '¿Estás seguro de eliminar esta categoría? Esta acción no se puede deshacer.',
                                                onConfirm: () => $refs.deleteForm{{ $category->id }}.submit()
                                            })"
                                            class="flex items-center gap-1 text-red-500 hover:text-red-400 text-sm transition"
                                            title="Eliminar"
                                        >
                                            <!-- Trash Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="w-4 h-4"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                                stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0H7m2-3h6a1 1 0 011 1v1H8V5a1 1 0 011-1z"/>
                                            </svg>
                                            Eliminar
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-400">
                            No hay categorías registradas
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
