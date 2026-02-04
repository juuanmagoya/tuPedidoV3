@extends('layouts.app')

@section('title', 'Detalle de compra')

@section('content')
<div class="max-w-5xl space-y-8 pb-24">

    {{-- HEADER --}}
    <div class="flex items-start justify-between mt-10">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                Compra #{{ $purchase->id }}
            </h1>
            <p class="text-sm text-gray-400">
                Detalle de la compra de insumos
            </p>
        </div>

        <a
            href="{{ route('purchases.index') }}"
            class="px-4 py-2 border border-[#1F2933]
                   rounded-lg text-gray-300 hover:bg-[#0B1220]"
        >
            Volver
        </a>
    </div>

    {{-- INFO GENERAL --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        {{-- Proveedor --}}
        <div class="bg-[#111827] border border-[#1F2933]
                    rounded-xl p-4">
            <p class="text-xs text-gray-400">Proveedor</p>
            <p class="text-white font-semibold">
                {{ $purchase->supplier->name }}
            </p>
        </div>

        {{-- Fecha --}}
        <div class="bg-[#111827] border border-[#1F2933]
                    rounded-xl p-4">
            <p class="text-xs text-gray-400">Fecha de compra</p>
            <p class="text-white font-semibold">
                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}
            </p>
        </div>

        {{-- Estado --}}

        <div class="bg-[#111827] border border-[#1F2933]
                    rounded-xl p-4">
            <p class="text-xs text-gray-400">Estado</p>

            @php
                $statusMap = [
                    'pending'    => ['label' => 'Pendiente',    'color' => 'text-yellow-400'],
                    'approved'   => ['label' => 'Aprobada',     'color' => 'text-blue-400'],
                    'in_transit' => ['label' => 'En tránsito',  'color' => 'text-purple-400'],
                    'completed'  => ['label' => 'Completada',   'color' => 'text-green-400'],
                    'cancelled'  => ['label' => 'Cancelada',    'color' => 'text-red-400'],
                ];

                $status = $statusMap[$purchase->status] ?? [
                    'label' => 'Desconocido',
                    'color' => 'text-gray-300',
                ];
            @endphp

            <p class="font-semibold {{ $status['color'] }}">
                {{ $status['label'] }}
            </p>
        </div>

    </div>

    {{-- NOTAS --}}
    @if($purchase->notes)
        <div class="bg-[#111827] border border-[#1F2933]
                    rounded-xl p-4">
            <p class="text-xs text-gray-400 mb-1">Notas</p>
            <p class="text-gray-200">
                {{ $purchase->notes }}
            </p>
        </div>
    @endif

    {{-- ITEMS --}}
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl overflow-hidden shadow-lg">

        <table class="w-full text-sm">
            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Insumo</th>
                    <th class="px-6 py-4 text-center">Unidad</th>
                    <th class="px-6 py-4 text-right">Cantidad</th>
                    <th class="px-6 py-4 text-right">Precio unitario</th>
                    <th class="px-6 py-4 text-right">Total</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">
                @foreach($purchase->items as $item)
                    <tr class="hover:bg-[#0B1220] transition">

                        <td class="px-6 py-4 text-white">
                            {{ $item->input->name }}
                        </td>

                        <td class="px-6 py-4 text-center text-gray-400">
                            {{ $item->unit }}
                        </td>

                        <td class="px-6 py-4 text-right text-gray-300">
                            {{ number_format($item->quantity, 3, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-right text-gray-300">
                            $ {{ number_format($item->unit_price, 2, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-right text-white font-semibold">
                            $ {{ number_format($item->total_price, 2, ',', '.') }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOTALES --}}
    <div class="flex justify-end">
        <div class="w-full md:w-1/3 bg-[#111827]
                    border border-[#1F2933]
                    rounded-xl p-4 space-y-2">

            <div class="flex justify-between text-gray-300">
                <span>Subtotal</span>
                <span>
                    $ {{ number_format($purchase->subtotal, 2, ',', '.') }}
                </span>
            </div>

            <div class="flex justify-between text-white font-semibold text-lg">
                <span>Total</span>
                <span>
                    $ {{ number_format($purchase->total, 2, ',', '.') }}
                </span>
            </div>
        </div>
    </div>

</div>
@endsection
