@extends('layouts.app')

@section('title', 'Compras')

@section('content')
<div class="space-y-10 pb-24">

@if ($errors->any())
    <div class="text-red-500 text-sm">
        {{ $errors->first() }}
    </div>
@endif

{{-- HEADER --}}
<div class="flex items-center justify-between mt-10">
    <div>
        <h1 class="text-2xl font-semibold text-white">Compras</h1>
        <p class="text-sm text-gray-400">
            Registro de compras de insumos a proveedores
        </p>
    </div>

    <a href="{{ route('purchases.create') }}"
       class="inline-flex items-center gap-2 bg-[#F59E0B] hover:bg-[#FBBF24]
              text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
        + Nueva compra
    </a>
</div>

{{-- FILTROS --}}
<form method="GET" class="bg-[#111827] border border-[#1F2933] rounded-xl p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

        {{-- Estado --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">Estado</label>
            <select
                name="status"
                class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white"
            >
                <option value="">Todos</option>
                <option value="pending" @selected(request('status') === 'pending')>Pendiente</option>
                <option value="approved" @selected(request('status') === 'approved')>Aprobada</option>
                <option value="in_transit" @selected(request('status') === 'in_transit')>En camino</option>
                <option value="completed" @selected(request('status') === 'completed')>Completada</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelada</option>
            </select>
        </div>

        {{-- Desde --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">Desde</label>
            <input
                type="date"
                name="from"
                value="{{ request('from') }}"
                class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white"
                style="color-scheme: dark;"
            >
        </div>

        {{-- Hasta --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">Hasta</label>
            <input
                type="date"
                name="to"
                value="{{ request('to') }}"
                class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white"
                style="color-scheme: dark;"
            >
        </div>

        {{-- Acciones --}}
        <div class="flex gap-2">
            <button
                type="submit"
                class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-4 py-2 rounded-lg font-semibold"
            >
                Filtrar
            </button>

            <a
                href="{{ route('purchases.index') }}"
                class="px-4 py-2 border border-[#1F2933] rounded-lg text-gray-300 hover:bg-[#0B1220]"
            >
                Limpiar
            </a>
        </div>
    </div>
</form>

{{-- TABLA --}}
<div class="bg-[#111827] border border-[#1F2933]
            rounded-2xl overflow-hidden shadow-lg">

    <table class="w-full text-sm">
        <thead class="bg-[#0B1220] text-gray-400">
            <tr>
                <th class="px-6 py-4 text-left">Proveedor</th>
                <th class="px-6 py-4 text-left">Fecha</th>
                <th class="px-6 py-4 text-left">Estado</th>
                <th class="px-6 py-4 text-right">Acciones</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-[#1F2933]">

        @forelse($purchases as $purchase)
            <tr class="hover:bg-[#0B1220] transition">

                <td class="px-6 py-4 text-white font-medium">
                    {{ $purchase->supplier->name }}
                </td>

                <td class="px-6 py-4 text-gray-400">
                    {{ $purchase->purchase_date }}
                </td>

                {{-- Estado --}}
                <td class="px-6 py-4">
                    <form
                        method="POST"
                        action="{{ route('purchases.change-status', $purchase) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <select
                            name="status"
                            onchange="this.form.submit()"
                            @if($purchase->status === 'cancelled') disabled @endif
                            class="w-full rounded-md px-3 py-1.5 text-sm
                                   border border-[#3a5168] bg-[#3d485f]"
                        >
                            @foreach([
                                'pending' => 'Pendiente',
                                'approved' => 'Aprobada',
                                'in_transit' => 'En camino',
                                'completed' => 'Completada',
                                'cancelled' => 'Cancelada'
                            ] as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected($purchase->status === $value)
                                >
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </td>

                {{-- Acciones --}}
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-4">

                        {{-- EDITAR --}}
                        @if(in_array($purchase->status, ['pending', 'approved']))
                            <a
                                href="{{ route('purchases.edit', $purchase) }}"
                                class="flex items-center gap-1 text-[#F59E0B]
                                    hover:text-[#FBBF24] text-sm transition"
                            >
                                {{-- Ícono lápiz --}}
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
                        @endif

                        {{-- CANCELAR --}}
                        @if(!in_array($purchase->status, ['cancelled', 'completed']))
                            <div x-data>
                                <form
                                    x-ref="cancelForm{{ $purchase->id }}"
                                    action="{{ route('purchases.cancel', $purchase) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="button"
                                        @click="$store.modal.show({
                                            title: 'Cancelar compra',
                                            message: '¿Estás seguro de cancelar esta compra?',
                                            onConfirm: () => $refs.cancelForm{{ $purchase->id }}.submit()
                                        })"
                                        class="flex items-center gap-1 text-red-500
                                            hover:text-red-400 text-sm transition"
                                    >
                                        {{-- Ícono papelera --}}
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
                                        Cancelar
                                    </button>
                                </form>
                            </div>
                        @endif

                        {{-- VER DETALLE --}}
                        <a
                            href="{{ route('purchases.show', $purchase) }}"
                            class="flex items-center gap-1 text-[#F59E0B]
                                hover:text-[#FBBF24] text-sm transition"
                        >
                            {{-- Ícono ojo --}}
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0
                                        3 3 0 016 0z"/>
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943 7.523 5
                                        12 5c4.477 0 8.268 2.943
                                        9.542 7-1.274 4.057-5.065
                                        7-9.542 7-4.477 0-8.268-2.943
                                        -9.542-7z"/>
                            </svg>
                            Ver
                        </a>

                    </div>
                </td>


            </tr>
        @empty
            <tr>
                <td colspan="4"
                    class="px-6 py-10 text-center text-gray-400">
                    No hay compras registradas
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>
</div>
</div>
@endsection
