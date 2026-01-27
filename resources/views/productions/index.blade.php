@extends('layouts.app')

@section('title', 'Producciones')

@section('content')
<div class="space-y-10 pb-24">

    <!-- HEADER -->
    <div class="flex items-center justify-between mt-10">
        <div>
            <h1 class="text-2xl font-semibold text-white">Producciones</h1>
            <p class="text-sm text-gray-400">
                Registro de producciones realizadas
            </p>
        </div>

        <a href="{{ route('productions.create') }}"
           class="inline-flex items-center gap-2 bg-[#F59E0B] hover:bg-[#FBBF24]
                  text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
            + Nueva producción
        </a>
    </div>

    <!-- TABLA -->
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl overflow-hidden shadow-lg">

        <table class="w-full text-sm">
            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Código</th>
                    <th class="px-6 py-4 text-left">Fecha</th>
                    <th class="px-6 py-4 text-left">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">

                @forelse($productions as $production)
                    <tr class="hover:bg-[#0B1220] transition">

                        <td class="px-6 py-4 text-white font-medium">
                            {{ $production->code }}
                        </td>

                        <td class="px-6 py-4 text-gray-400">
                            {{ $production->production_date }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs
                                {{ $production->status === 'confirmed'
                                    ? 'bg-green-500/20 text-green-400'
                                    : 'bg-gray-500/20 text-gray-400' }}">
                                {{ ucfirst($production->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('productions.show', $production->id) }}"
                               class="text-[#F59E0B] hover:text-[#FBBF24] text-sm">
                                Ver detalle
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="px-6 py-10 text-center text-gray-400">
                            No hay producciones registradas
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>
@endsection
