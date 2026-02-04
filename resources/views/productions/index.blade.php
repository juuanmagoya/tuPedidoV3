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

    <form method="GET" class="bg-[#111827] border border-[#1F2933] rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

            <!-- Estado -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Estado</label>
                <select
                    name="status"
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white"
                >
                    <option value="">Todos</option>
                    <option value="draft" @selected(request('status') === 'draft')>Borrador</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmada</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelada</option>
                </select>
            </div>

            <!-- Desde -->
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

            <!-- Hasta -->
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

            <!-- Acciones -->
            <div class="flex gap-2">
                <button
                    type="submit"
                    class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-4 py-2 rounded-lg font-semibold"
                >
                    Filtrar
                </button>

                <a
                    href="{{ route('productions.index') }}"
                    class="px-4 py-2 border border-[#1F2933] rounded-lg text-gray-300 hover:bg-[#0B1220]"
                >
                    Limpiar
                </a>
            </div>
        </div>
    </form>

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
                            @if($production->status !== 'cancelled')
                                <a href="{{ route('productions.edit', $production->id) }}"
                                class="text-blue-400 hover:text-blue-300 text-sm mr-3">
                                    Editar
                                </a>
                            @endif

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
