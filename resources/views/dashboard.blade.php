@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-white">Dashboard</h1>
        <p class="text-sm text-gray-400">Resumen general del negocio</p>
    </div>

    <!-- Filtros de fechas y periodos rápidos -->
    <div class="flex flex-wrap gap-2 items-end">
        <form class="flex gap-2" method="GET" action="{{ route('dashboard') }}">
            <!-- Filtrado manual desde/hasta -->
            <input type="date" name="from" value="{{ request('from') }}" class="bg-gray-800 text-white rounded-lg px-3 py-2">
            <input type="date" name="to" value="{{ request('to') }}" class="bg-gray-800 text-white rounded-lg px-3 py-2">
            <button class="bg-indigo-600 hover:bg-indigo-500 px-4 py-2 rounded-lg">Filtrar</button>
        </form>

        <!-- Filtros rápidos: Hoy, 7 días, 30 días, Este mes -->
        <div class="flex gap-2">
            @foreach (['today'=>'Hoy','7'=>'7 días','30'=>'30 días','month'=>'Este mes'] as $key => $label)
                <a href="{{ route('dashboard', array_merge(request()->except('period','from','to'), ['period'=>$key])) }}"
                   class="px-3 py-2 rounded-lg text-sm {{ request('period')==$key ? 'bg-indigo-600' : 'bg-gray-800 hover:bg-gray-700' }} text-white">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- KPIs principales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800">
            <p class="text-gray-400 text-sm">Total Ventas</p>
            <h3 class="text-3xl font-bold text-white">${{ number_format($dashboard['kpis']['sales_total'],0) }}</h3>
            <p class="text-sm text-gray-400 mt-2">{{ $dashboard['kpis']['products_sold'] }} productos vendidos</p>
        </div>

        <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800">
            <p class="text-gray-400 text-sm">Total Compras</p>
            <h3 class="text-3xl font-bold text-white">${{ number_format($dashboard['kpis']['purchases_total'],0) }}</h3>
            <p class="text-sm text-gray-400 mt-2">{{ $dashboard['kpis']['products_bought'] }} productos comprados</p>
        </div>

        <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800">
            <p class="text-gray-400 text-sm">Ganancia Neta</p>
            <h3 class="text-3xl font-bold {{ $dashboard['kpis']['profit']>=0 ? 'text-green-400':'text-red-400' }}">
                ${{ number_format($dashboard['kpis']['profit'],0) }}
            </h3>
            <span class="text-sm mt-2 inline-block {{ $dashboard['kpis']['profit']>=0 ? 'text-green-400':'text-red-400' }}">
                {{ $dashboard['kpis']['profit_percent'] }}%
                {{ $dashboard['kpis']['profit']>=0 ? 'Ganancia' : 'Pérdida' }}
            </span>
        </div>
    </div>

    <!-- Métricas rápidas -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        @foreach ($dashboard['quick'] as $label => $value)
            <div class="bg-gray-900 p-4 rounded-xl border border-gray-800 text-center">
                <p class="text-gray-400 text-xs uppercase">{{ $label }}</p>
                <p class="text-xl font-semibold text-white">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <!-- KPIs de estados de pedidos -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-6">
        @php
            $statusCards = [
                ['label'=>'Recibidos','value'=>'orders_received','color'=>'yellow-400'],
                ['label'=>'Preparando','value'=>'orders_preparing','color'=>'yellow-400'],
                ['label'=>'En Camino','value'=>'orders_on_the_way','color'=>'yellow-400'],
                ['label'=>'Entregados','value'=>'orders_delivered','color'=>'green-400'],
                ['label'=>'Cancelados','value'=>'orders_cancelled','color'=>'red-400'],
                ['label'=>'Compras completadas','value'=>'completed_purchases','color'=>'blue-400'],
            ];
        @endphp

        @foreach ($statusCards as $card)
            <div class="bg-[#111827] border border-gray-800 rounded-xl p-4">
                <p class="text-xs text-gray-400">{{ $card['label'] }}</p>
                <p class="text-2xl font-bold text-{{ $card['color'] }}">
                    {{ $dashboard['kpis'][$card['value']] }}
                </p>
            </div>
        @endforeach
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-4 h-[300px]">
            <h3 class="text-sm font-semibold text-white mb-2">Métodos de pago</h3>
            <canvas id="paymentMethodChart" class="max-h-[220px]"></canvas>
        </div>
        <div class="bg-[#111827] border border-gray-800 rounded-xl p-4 h-[300px]">
            <h3 class="text-sm font-semibold text-white mb-2">Top productos vendidos</h3>
            <canvas id="topProductsChart" class="max-h-[220px]"></canvas>
        </div>
    </div>

    <!-- Pedidos recientes -->
    <div class="bg-[#111827] p-6 rounded-2xl border border-gray-800">
        <div class="flex justify-between mb-4">
            <h3 class="text-lg font-semibold text-white">Ventas recientes</h3>
            <a href="{{ route('orders.index') }}" class="text-indigo-400 text-sm">Ver todas →</a>
        </div>

        @forelse ($dashboard['recent_orders'] as $order)
            <div class="flex items-center justify-between gap-4 py-3 border-b border-gray-800 hover:bg-gray-800/40 rounded-lg px-2 transition">
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-white">Pedido #{{ $order->id }} |---| Cliente: {{ $order->customer_name }} </span>
                    <div class="flex gap-2 mt-1 text-xs text-gray-400">
                        <span class="px-2 py-0.5 rounded-full bg-gray-700">{{ ucfirst($order->order_type) }}</span>
                        <span class="px-2 py-0.5 rounded-full bg-gray-700">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    
                </div>
                <div class="text-right">
                    <span class="text-sm font-semibold text-green-400">${{ number_format($order->total,0) }}</span>
                </div>
            </div>
        @empty
            <p class="text-gray-400 text-sm mt-2">No hay pedidos en este periodo.</p>
        @endforelse
    </div>

    <!-- Scripts de Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        console.log(@json($dashboard['payment_methods']));
console.log(@json($dashboard['top_products_chart']));

        document.addEventListener('DOMContentLoaded', function () {
    const paymentData = @json($dashboard['payment_methods']);
    new Chart(document.getElementById('paymentMethodChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(paymentData),
            datasets: [{
                data: Object.values(paymentData),
                backgroundColor: ['#22c55e','#3b82f6','#f59e0b','#ef4444','#8b5cf6']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { color:'#d1d5db', boxWidth:12, padding:12 } }
            }
        }
    });

    const topProducts = @json($dashboard['top_products_chart']);
    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(topProducts),
            datasets: [{ label:'Cantidad vendida', data:Object.values(topProducts), backgroundColor:'#3b82f6' }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { ticks:{ color:'#d1d5db', font:{ size:10 } }, grid:{ display:false } },
                y: { ticks:{ color:'#d1d5db', font:{ size:10 } }, grid:{ color:'#1f2937' } }
            },
            plugins: { legend:{ display:false } }
        }
    });
});

    </script>
</div>
@endsection
