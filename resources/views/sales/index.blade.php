@extends('layouts.app')

@section('title', 'Ventas')

@section('content')
<div class="space-y-10 pb-24">

    <!-- HEADER -->
    <div class="flex items-center justify-between mt-10">
        <div>
            <h1 class="text-2xl font-semibold text-white">Ventas</h1>
            <p class="text-sm text-gray-400">
                Ventas generadas automáticamente desde pedidos entregados
            </p>
        </div>
    </div>

    <!-- =========================
         FILTROS
         ========================= -->
    <form method="GET"
          class="bg-[#111827] border border-[#1F2933]
                 rounded-2xl p-6 flex flex-wrap gap-6 items-end">

        <!-- Fecha desde -->
        <div>
            <label class="block text-sm text-gray-400 mb-1">Desde</label>
            <input type="date"
                   name="date_from"
                   value="{{ request('date_from') }}"
                   class="bg-[#0B1220] border border-[#1F2933]
                          text-white rounded-lg px-3 py-2 text-sm">
        </div>

        <!-- Fecha hasta -->
        <div>
            <label class="block text-sm text-gray-400 mb-1">Hasta</label>
            <input type="date"
                   name="date_to"
                   value="{{ request('date_to') }}"
                   class="bg-[#0B1220] border border-[#1F2933]
                          text-white rounded-lg px-3 py-2 text-sm">
        </div>

        <!-- Método de pago -->
        <div>
            <label class="block text-sm text-gray-400 mb-1">Método de pago</label>
            <select name="payment_method"
                    class="bg-[#0B1220] border border-[#1F2933]
                           text-white rounded-lg px-3 py-2 text-sm">

                <option value="">Todos</option>
                <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Efectivo</option>
                <option value="card" {{ request('payment_method') == 'card' ? 'selected' : '' }}>Tarjeta</option>
                <option value="qr" {{ request('payment_method') == 'qr' ? 'selected' : '' }}>QR</option>
                <option value="other" {{ request('payment_method') == 'other' ? 'selected' : '' }}>Otro</option>
            </select>
        </div>

        <!-- Botones -->
        <div class="flex gap-3">
            <button type="submit"
                class="bg-[#F59E0B] hover:bg-[#FBBF24]
                       text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
                Filtrar
            </button>

            <a href="{{ route('sales.index') }}"
               class="bg-gray-700 hover:bg-gray-600
                      text-white px-4 py-2 rounded-lg text-sm transition">
                Limpiar
            </a>
        </div>
    </form>

    <!-- =========================
         TABLA
         ========================= -->
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl overflow-hidden shadow-lg">

        <table class="w-full text-sm">

            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">#</th>
                    <th class="px-6 py-4 text-left">Pedido</th>
                    <th class="px-6 py-4 text-left">Método</th>
                    <th class="px-6 py-4 text-left">Total</th>
                    <th class="px-6 py-4 text-left">Fecha</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">

                @forelse($sales as $sale)
                    <tr class="hover:bg-[#0B1220] transition">

                        <td class="px-6 py-4 text-gray-400">
                            {{ $sale->id }}
                        </td>

                        <td class="px-6 py-4 text-white font-medium">
                            Pedido #{{ $sale->order->id }}
                        </td>

                        <td class="px-6 py-4 text-gray-300">
                            @switch($sale->payment_method)
                                @case('cash') Efectivo @break
                                @case('card') Tarjeta @break
                                @case('qr') QR @break
                                @default Otro
                            @endswitch
                        </td>

                        <td class="px-6 py-4 text-green-400 font-semibold">
                            $ {{ number_format($sale->total, 2) }}
                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            {{ $sale->sold_at->format('d/m/Y H:i') }}
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('sales.show', $sale) }}"
                               class="text-[#F59E0B] hover:text-[#FBBF24] text-sm transition">
                                Ver
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6"
                            class="px-6 py-10 text-center text-gray-400">
                            No hay ventas registradas
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>

        <div class="p-6">
            {{ $sales->links() }}
        </div>
    </div>

</div>
@endsection
