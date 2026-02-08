@extends('layouts.app')

@section('title', 'Detalle del pedido')

@section('content')
<div class="max-w-5xl space-y-8 pb-24">

    {{-- HEADER --}}
    <div class="flex items-start justify-between mt-10">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                Pedido #{{ $order->id }}
            </h1>
            <p class="text-sm text-gray-400">
                Detalle del pedido
            </p>
        </div>

        <a
            href="{{ route('orders.index') }}"
            class="px-4 py-2 border border-[#1F2933]
                   rounded-lg text-gray-300 hover:bg-[#0B1220]"
        >
            Volver
        </a>
    </div>

    {{-- INFO GENERAL --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Cliente --}}
        <div class="bg-[#111827] border border-[#1F2933]
                    rounded-xl p-4">
            <p class="text-xs text-gray-400">Cliente</p>
            <p class="text-white font-semibold">
                {{ $order->customer_name }}
            </p>
        </div>

        {{-- Tipo de pedido --}}
        <div class="bg-[#111827] border border-[#1F2933]
                    rounded-xl p-4">
            <p class="text-xs text-gray-400">Tipo de pedido</p>
            <p class="text-white font-semibold capitalize">
                {{ $order->order_type === 'delivery' ? 'Delivery' : 'En local' }}
            </p>
        </div>

        {{-- Estado --}}
        <div class="bg-[#111827] border border-[#1F2933]
                    rounded-xl p-4">
            <p class="text-xs text-gray-400">Estado</p>

            @php
                $statusMap = [
                    'received'    => ['label' => 'Recibido',        'color' => 'text-blue-400'],
                    'preparing'   => ['label' => 'En preparación', 'color' => 'text-yellow-400'],
                    'on_the_way'  => ['label' => 'En camino',      'color' => 'text-purple-400'],
                    'delivered'   => ['label' => 'Entregado',      'color' => 'text-green-400'],
                    'canceled'    => ['label' => 'Cancelado',      'color' => 'text-red-400'],
                ];

                $status = $statusMap[$order->status] ?? [
                    'label' => 'Desconocido',
                    'color' => 'text-gray-300',
                ];
            @endphp

            <p class="font-semibold {{ $status['color'] }}">
                {{ $status['label'] }}
            </p>
        </div>

    </div>

    {{-- DIRECCIÓN --}}
    @if($order->order_type === 'delivery' && $order->address)
        <div class="bg-[#111827] border border-[#1F2933]
                    rounded-xl p-4">
            <p class="text-xs text-gray-400 mb-1">Dirección de entrega</p>
            <p class="text-gray-200">
                {{ $order->address }}
            </p>
        </div>
    @endif

    {{-- NOTAS --}}
    @if($order->notes)
        <div class="bg-[#111827] border border-[#1F2933]
                    rounded-xl p-4">
            <p class="text-xs text-gray-400 mb-1">Notas</p>
            <p class="text-gray-200">
                {{ $order->notes }}
            </p>
        </div>
    @endif

    {{-- ITEMS --}}
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl overflow-hidden shadow-lg">

        <table class="w-full text-sm">
            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Producto</th>
                    <th class="px-6 py-4 text-center">Cantidad</th>
                    <th class="px-6 py-4 text-right">Precio unitario</th>
                    <th class="px-6 py-4 text-right">Subtotal</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">
                @foreach($order->products as $item)
                    <tr class="hover:bg-[#0B1220] transition">

                        <td class="px-6 py-4 text-white">
                            {{ $item->product->name }}
                        </td>

                        <td class="px-6 py-4 text-center text-gray-300">
                            {{ $item->quantity }}
                        </td>

                        <td class="px-6 py-4 text-right text-gray-300">
                            $ {{ number_format($item->unit_price, 2, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-right text-white font-semibold">
                            $ {{ number_format($item->subtotal, 2, ',', '.') }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOTAL --}}
    <div class="flex justify-end">
        <div class="w-full md:w-1/3 bg-[#111827]
                    border border-[#1F2933]
                    rounded-xl p-4 space-y-2">

            <div class="flex justify-between text-white font-semibold text-lg">
                <span>Total</span>
                <span>
                    $ {{ number_format($order->total, 2, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

</div>
@endsection
