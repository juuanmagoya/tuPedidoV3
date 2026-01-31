@extends('layouts.app')

@section('title', 'Detalle de Producción')

@section('content')
<div class="space-y-8 pb-24 max-w-5xl">

    <!-- HEADER -->
    <div class="flex items-center justify-between mt-10">
        <div>
            <h1 class="text-2xl font-semibold text-white">
                Producción {{ $production->code }}
            </h1>
            <p class="text-sm text-gray-400">
                Detalle de la producción registrada
            </p>
        </div>

        <a href="{{ route('productions.index') }}"
           class="px-4 py-2 border border-[#1F2933]
                  rounded-lg text-gray-300 hover:bg-[#0B1220] transition">
            Volver
        </a>
    </div>

    <!-- ======================
         RESUMEN
         ====================== -->
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl p-6 shadow-lg">

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-sm">

            <div>
                <p class="text-gray-400">Código</p>
                <p class="text-white font-medium">
                    {{ $production->code }}
                </p>
            </div>

            <div>
                <p class="text-gray-400">Fecha</p>
                <p class="text-white font-medium">
                    {{ $production->production_date }}
                </p>
            </div>

            <div>
                <p class="text-gray-400">Estado</p>
                <span class="inline-block mt-1 px-2 py-1 rounded text-xs
                    {{ $production->status === 'confirmed'
                        ? 'bg-green-500/20 text-green-400'
                        : 'bg-gray-500/20 text-gray-400' }}">
                    {{ ucfirst($production->status) }}
                </span>
            </div>

            <div>
                <p class="text-gray-400">Registrado por</p>
                <p class="text-white font-medium">
                    {{ $production->creator->name ?? '—' }}
                </p>
            </div>

        </div>
    </div>

    <!-- ======================
         INSUMOS CONSUMIDOS
         ====================== -->
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl shadow-lg overflow-hidden">

        <div class="px-6 py-4 border-b border-[#1F2933]">
            <h2 class="text-white font-semibold">
                Insumos consumidos
            </h2>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-3 text-left">Insumo</th>
                    <th class="px-6 py-3 text-right">Cantidad utilizada</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">
                @foreach($production->inputs as $row)
                    <tr>
                        <td class="px-6 py-4 text-white">
                            {{ $row->input->name }}
                        </td>

                        <td class="px-6 py-4 text-right text-gray-300">
                            {{ rtrim(rtrim(number_format($row->quantity_used, 3, '.', ''), '0'), '.') }} {{ $row->unit }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ======================
         PRODUCTOS GENERADOS
         ====================== -->
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl shadow-lg overflow-hidden">

        <div class="px-6 py-4 border-b border-[#1F2933]">
            <h2 class="text-white font-semibold">
                Productos generados
            </h2>
        </div>

        <table class="w-full text-sm">
            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-3 text-left">Producto</th>
                    <th class="px-6 py-3 text-right">Cantidad producida</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">
                @foreach($production->products as $row)
                    <tr>
                        <td class="px-6 py-4 text-white">
                            {{ $row->product->name }}
                        </td>

                        <td class="px-6 py-4 text-right text-gray-300">
                            {{ rtrim(rtrim(number_format($row->quantity_produced, 3, '.', ''), '0'), '.') }} {{ $row->unit }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
