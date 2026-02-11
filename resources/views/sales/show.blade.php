@extends('layouts.app')

@section('title', 'Detalle de Venta')

@section('content')
<div class="space-y-10 pb-24">

    <div class="mt-10">
        <h1 class="text-2xl font-semibold text-white">
            Detalle de Venta #{{ $sale->id }}
        </h1>
        <p class="text-sm text-gray-400">
            Información de la venta generada desde el pedido
        </p>
    </div>

    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl p-8 shadow-lg space-y-6">

        <div>
            <p class="text-sm text-gray-400">Pedido asociado</p>
            <p class="text-white font-medium">
                Pedido #{{ $sale->order->id }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-400">Método de pago</p>
            <p class="text-white font-medium">
                @switch($sale->payment_method)
                    @case('cash') Efectivo @break
                    @case('card') Tarjeta @break
                    @case('qr') QR @break
                    @default Otro
                @endswitch
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-400">Total</p>
            <p class="text-green-400 text-xl font-semibold">
                $ {{ number_format($sale->total, 2) }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-400">Fecha de venta</p>
            <p class="text-white">
                {{ $sale->sold_at->format('d/m/Y H:i') }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-400">Estado del pedido</p>
            <p class="text-white">
                Entregado
            </p>
        </div>


        <div class="pt-6">
            <a href="{{ route('sales.index') }}"
               class="bg-gray-700 hover:bg-gray-600
                      text-white px-4 py-2 rounded-lg text-sm transition">
                Volver
            </a>
        </div>

    </div>

</div>
@endsection
