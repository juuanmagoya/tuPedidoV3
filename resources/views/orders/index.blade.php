@extends('layouts.app')

@section('title', 'Pedidos')

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
        <h1 class="text-2xl font-semibold text-white">Pedidos</h1>
        <p class="text-sm text-gray-400">
            Gestión de pedidos de clientes
        </p>
    </div>

    <a href="{{ route('orders.create') }}"
       class="inline-flex items-center gap-2 bg-[#F59E0B] hover:bg-[#FBBF24]
              text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
        + Nuevo pedido
    </a>
</div>

{{-- FILTROS --}}
<form method="GET"
      class="bg-[#111827] border border-[#1F2933] rounded-xl p-4 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

        {{-- Estado --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">Estado</label>
            <select
                name="status"
                class="w-full bg-[#0B1220] border border-[#1F2933]
                       rounded-lg px-3 py-2 text-white"
            >
                <option value="">Todos</option>
                <option value="received" @selected(request('status') === 'received')>Recibido</option>
                <option value="preparing" @selected(request('status') === 'preparing')>En preparación</option>
                <option value="on_the_way" @selected(request('status') === 'on_the_way')>En camino</option>
                <option value="delivered" @selected(request('status') === 'delivered')>Entregado</option>
                <option value="canceled" @selected(request('status') === 'canceled')>Cancelado</option>
            </select>
        </div>

        {{-- Desde --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">Desde</label>
            <input
                type="date"
                name="from"
                value="{{ request('from') }}"
                class="w-full bg-[#0B1220] border border-[#1F2933]
                       rounded-lg px-3 py-2 text-white"
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
                class="w-full bg-[#0B1220] border border-[#1F2933]
                       rounded-lg px-3 py-2 text-white"
                style="color-scheme: dark;"
            >
        </div>

        {{-- Acciones --}}
        <div class="flex gap-2">
            <button
                type="submit"
                class="bg-[#F59E0B] hover:bg-[#FBBF24]
                       text-black px-4 py-2 rounded-lg font-semibold"
            >
                Filtrar
            </button>

            <a
                href="{{ route('orders.index') }}"
                class="px-4 py-2 border border-[#1F2933]
                       rounded-lg text-gray-300 hover:bg-[#0B1220]"
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
                <th class="px-6 py-4 text-left">Cliente</th>
                <th class="px-6 py-4 text-left">Fecha</th>
                <th class="px-6 py-4 text-left">Estado</th>
                <th class="px-6 py-4 text-right">Acciones</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-[#1F2933]">

        @forelse($orders as $order)
            <tr class="hover:bg-[#0B1220] transition">

                {{-- Cliente --}}
                <td class="px-6 py-4 text-white font-medium">
                    {{ $order->customer_name }}
                </td>

                {{-- Fecha --}}
                <td class="px-6 py-4 text-gray-400">
                    {{ $order->created_at->format('d/m/Y') }}
                </td>

                {{-- Estado --}}
                <td class="px-6 py-4">
                    <form
                        method="POST"
                        action="{{ route('orders.change-status', $order) }}"
                    >
                        @csrf
                        @method('PATCH')

                        <select
                            name="status"
                            onchange="this.form.submit()"
                            @if($order->status === 'delivered' || $order->status === 'canceled') disabled @endif
                            class="w-full rounded-md px-3 py-1.5 text-sm
                                border border-[#3a5168] bg-[#3d485f] text-white"
                        >

                            @foreach([
                                'received'   => 'Recibido',
                                'preparing'  => 'En preparación',
                                'on_the_way' => 'En camino',
                                'delivered'  => 'Entregado',
                                'canceled'   => 'Cancelado',
                            ] as $value => $label)

                                {{-- Mostrar solo transiciones válidas --}}
                                @if(
                                    $order->status === $value ||

                                    ($order->status === 'received'   && in_array($value, ['preparing', 'canceled'])) ||
                                    ($order->status === 'preparing'  && in_array($value, ['on_the_way', 'canceled'])) ||
                                    ($order->status === 'on_the_way' && $value === 'delivered')
                                )
                                    <option
                                        value="{{ $value }}"
                                        @selected($order->status === $value)
                                    >
                                        {{ $label }}
                                    </option>
                                @endif

                            @endforeach
                        </select>
                    </form>
                </td>


                {{-- Acciones --}}
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-4">

                        {{-- EDITAR --}}
                        @if($order->canBeEdited())
                            <a
                                href="{{ route('orders.edit', $order) }}"
                                class="flex items-center gap-1 text-[#F59E0B]
                                       hover:text-[#FBBF24] text-sm transition"
                            >
                                {{-- ícono lápiz --}}
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M16.862 3.487a2.25 2.25 0
                                           013.182 3.182L7.125 19.588
                                           3 21l1.412-4.125
                                           L16.862 3.487z"/>
                                </svg>
                                Editar
                            </a>
                        @endif

                        {{-- CANCELAR --}}
                        @if(!in_array($order->status, ['cancelled', 'delivered']))
                            <div x-data>
                                <form
                                    x-ref="cancelForm{{ $order->id }}"
                                    action="{{ route('orders.cancel', $order) }}"
                                    method="POST"
                                >
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="button"
                                        @click="$store.modal.show({
                                            title: 'Cancelar pedido',
                                            message: '¿Seguro que deseas cancelar este pedido?',
                                            onConfirm: () => $refs.cancelForm{{ $order->id }}.submit()
                                        })"
                                        class="flex items-center gap-1 text-red-500
                                               hover:text-red-400 text-sm transition"
                                    >
                                        {{-- ícono papelera --}}
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4" fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
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

                        {{-- VER --}}
                        <a
                            href="{{ route('orders.show', $order) }}"
                            class="flex items-center gap-1 text-[#F59E0B]
                                   hover:text-[#FBBF24] text-sm transition"
                        >
                            {{-- ícono ojo --}}
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-4 h-4" fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15 12a3 3 0 11-6 0
                                       3 3 0 016 0z"/>
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M2.458 12C3.732 7.943
                                       7.523 5 12 5c4.477 0
                                       8.268 2.943 9.542 7
                                       -1.274 4.057-5.065 7
                                       -9.542 7-4.477 0-8.268
                                       -2.943-9.542-7z"/>
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
                    No hay pedidos registrados
                </td>
            </tr>
        @endforelse

        </tbody>
    </table>
</div>
</div>
@endsection
