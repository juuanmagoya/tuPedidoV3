@extends('layouts.app')

@section('title', 'Proveedores')

@section('content')
<div class="space-y-10 pb-24">

    <!-- ================= HEADER ================= -->
    <div class="flex items-center justify-between mt-10">
        <div>
            <h1 class="text-2xl font-semibold text-white">Proveedores</h1>
            <p class="text-sm text-gray-400">
                Gestión de proveedores del sistema
            </p>
        </div>

        <a href="{{ route('suppliers.create') }}"
           class="inline-flex items-center gap-2 bg-[#F59E0B] hover:bg-[#FBBF24]
                  text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
            + Nuevo proveedor
        </a>
    </div>

    <!-- ================= TABLE ================= -->
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl overflow-hidden shadow-lg">

        <table class="w-full text-sm">

            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Nombre</th>
                    <th class="px-6 py-4 text-left">Contacto</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-center">Dirección</th>
                    <th class="px-6 py-4 text-center">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">

                @forelse($suppliers as $supplier)
                    <tr class="hover:bg-[#0B1220] transition">

                        <!-- Nombre -->
                        <td class="px-6 py-4 text-white font-medium">
                            {{ $supplier->name }}
                        </td>

                        <!-- Contacto -->
                        <td class="px-6 py-4 text-gray-300">
                            {{ $supplier->contact_name ?? '—' }}
                        </td>

                        <!-- Notas -->
                        <td class="px-6 py-4 text-gray-400">
                            {{ Str::limit($supplier->email, 50) ?? '—' }}
                        </td>

                        <!-- Notas -->
                        <td class="px-6 py-4 text-gray-400">
                            {{ Str::limit($supplier->address, 100) ?? '—' }}
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 text-center">
                            @if($supplier->is_active)
                                <span class="px-2 py-1 rounded-full text-xs
                                             bg-green-900 text-green-300">
                                    Activo
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs
                                             bg-red-900 text-red-300">
                                    Inactivo
                                </span>
                            @endif
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-4">

                                <a href="{{ route('suppliers.edit', $supplier) }}"
                                    class="text-[#F59E0B] hover:text-[#FBBF24] text-sm transition">
                                    <!-- Ícono lápiz -->
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-4 h-4"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M16.862 3.487a2.25 2.25 0 013.182 3.182
                                                    L7.125 19.588 3 21l1.412-4.125
                                                    L16.862 3.487z"/>
                                        </svg>
                                    Editar
                                </a>

                                <form action="{{ route('suppliers.destroy', $supplier) }}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="text-red-500 hover:text-red-400 text-sm transition">
                                        <!-- Ícono papelera -->
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                    stroke-width="2">
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
                                        Eliminar
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="px-6 py-10 text-center text-gray-400">
                            No hay proveedores registrados
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>
@endsection
