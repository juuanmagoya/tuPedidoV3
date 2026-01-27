@extends('layouts.app')

@section('title', 'Insumos')

@section('content')
<div class="space-y-10 pb-24">

    <!-- HEADER -->
    <div class="flex items-center justify-between mt-10">
        <div>
            <h1 class="text-2xl font-semibold text-white">Insumos</h1>
            <p class="text-sm text-gray-400">
                Gestión de insumos para producción
            </p>
        </div>

        <a href="{{ route('inputs.create') }}"
           class="inline-flex items-center gap-2 bg-[#F59E0B] hover:bg-[#FBBF24]
                  text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
            + Nuevo insumo
        </a>
    </div>
        <!-- =========================================================
        ALERTA DE BAJO STOCK
        ========================================================= -->
        <x-low-stock-alert
        :items="$lowStockInputs"
        title="¡Insumos con bajo stock!"
        entity="insumo"
        />

    <!-- TABLA -->
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl overflow-hidden shadow-lg">

        <table class="w-full text-sm">
            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Nombre</th>
                    <th class="px-6 py-4 text-left">Unidad</th>
                    <th class="px-6 py-4 text-left">Stock</th>
                    <th class="px-6 py-4 text-left">Stock mínimo</th>
                    <th class="px-6 py-4 text-left">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">
                @forelse($inputs as $input)
                    <tr class="hover:bg-[#0B1220] transition">
                        <td class="px-6 py-4 text-white font-medium">
                            {{ $input->name }}
                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            {{ $input->unit }}
                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            {{ rtrim(rtrim(number_format($input->stock, 3, '.', ''), '0'), '.') }} {{ $input->unit }}

                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            {{ rtrim(rtrim(number_format($input->min_stock, 3, '.', ''), '0'), '.') }} {{ $input->unit }}
                        </td>

                        <td class="px-6 py-4">
                            @if($input->is_active)
                                <span class="text-green-400 text-sm font-semibold">Activo</span>
                            @else
                                <span class="text-red-400 text-sm font-semibold">Inactivo</span>
                            @endif
                        </td>

                        <!-- ACCIONES -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">

                                <a href="{{ route('inputs.edit', $input) }}"
                                   class="flex items-center gap-1 text-[#F59E0B]
                                          hover:text-[#FBBF24] text-sm transition">
                                          <!-- Ícono lápiz -->
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="w-4 h-4"
                                         fill="none"
                                         viewBox="0 0 24 24"
                                         stroke="currentColor"
                                         stroke-width="2">
                                        <path stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M16.862 3.487a2.25 2.25 0 013.182 3.182
                                                 L7.125 19.588 3 21l1.412-4.125
                                                 L16.862 3.487z"/>
                                    </svg>
                                    Editar
                                </a>

                                <div x-data>
                                    <form
                                        x-ref="deleteForm{{ $input->id }}"
                                        action="{{ route('inputs.destroy', $input) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            @click="$store.modal.show({
                                                title: 'Eliminar insumo',
                                                message: '¿Estás seguro de eliminar este insumo?',
                                                onConfirm: () => $refs.deleteForm{{ $input->id }}.submit()
                                            })"
                                            class="flex items-center gap-1 text-red-500 hover:text-red-400 text-sm transition"
                                        >
                                        <!-- Ícono papelera -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142
                                                            A2 2 0 0116.138 21H7.862
                                                            a2 2 0 01-1.995-1.858L5 7
                                                            m5 4v6m4-6v6
                                                            M9 7h6m2 0H7
                                                            m2-3h6a1 1 0 011 1v1H8V5
                                                            a1 1 0 011-1z"/>
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
                        <td colspan="6"
                            class="px-6 py-10 text-center text-gray-400">
                            No hay insumos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
