@extends('layouts.app')

@section('title', 'Producciones')

@section('content')
<div class="space-y-10 pb-24">
@if ($errors->any())
    <div class="text-red-500 text-sm">
        {{ $errors->first() }}
    </div>
@endif

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

                        {{-- Estado --}}
                        <td class="px-6 py-4">
                            <form 
                                method="POST" 
                                action="{{ route('productions.change-status', $production) }}"
                            >
                                @csrf
                                @method('PATCH')

                                <select
                                    name="status"
                                    onchange="this.form.submit()"
                                    @if($production->status === 'cancelled') disabled @endif

                                    class="
                                        w-full rounded-md px-3 py-1.5 text-sm
                                        border border-[#3a5168]
                                        bg-[#3d485f]
                                        focus:outline-none focus:ring-2

                                        @if($production->status === 'draft')
                                            text-yellow
                                        @elseif($production->status === 'confirmed')
                                            text-green
                                        @elseif($production->status === 'cancelled')
                                        text-red cursor-not-allowed opacity-70
                                        @endif
                                    "
                                >
                                    {{-- draft --}}
                                    @if($production->status === 'draft')
                                        <option value="draft" selected class="text-yellow-400 bg-[#0B1220]">
                                            Borrador
                                        </option>
                                        <option value="confirmed" class="text-green-400 bg-[#0B1220]">
                                            Confirmado
                                        </option>
                                        <option value="cancelled" class="text-red-400 bg-[#0B1220]">
                                            Cancelado
                                        </option>
                                    @endif

                                    {{-- confirmed --}}
                                    @if($production->status === 'confirmed')
                                        <option value="confirmed" selected class="text-green-400 bg-[#17243d]">
                                            Confirmado
                                        </option>
                                        <option value="cancelled" class="text-red-400 bg-[#0B1220]">
                                            Cancelado
                                        </option>
                                    @endif

                                    {{-- cancelled --}}
                                    @if($production->status === 'cancelled')
                                        <option value="cancelled" selected class="text-red-400 bg-[#0B1220]">
                                            Cancelado
                                        </option>
                                    @endif
                                </select>
                            </form>
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
