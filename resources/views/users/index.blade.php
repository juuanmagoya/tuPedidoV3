@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
<div class="space-y-10 pb-24">

    <!-- =========================================================
         HEADER
         ========================================================= -->
    <div class="flex items-center justify-between mt-10">
        <div>
            <h1 class="text-2xl font-semibold text-white">Usuarios</h1>
            <p class="text-sm text-gray-400">
                Gestión de usuarios del sistema
            </p>
        </div>

        <a href="{{ route('users.create') }}"
           class="inline-flex items-center gap-2 bg-[#F59E0B] hover:bg-[#FBBF24]
                  text-black px-4 py-2 rounded-lg text-sm font-semibold transition">
            + Nuevo usuario
        </a>
    </div>

    <!-- =========================================================
         TABLA DE USUARIOS
         ========================================================= -->
    <div class="bg-[#111827] border border-[#1F2933]
                rounded-2xl overflow-hidden shadow-lg">

        <table class="w-full text-sm">

            <thead class="bg-[#0B1220] text-gray-400">
                <tr>
                    <th class="px-6 py-4 text-left">Nombre</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-left">Rol</th>
                    <th class="px-6 py-4 text-left">Estado</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-[#1F2933]">

                @forelse($users as $user)
                    <tr class="hover:bg-[#0B1220] transition">

                        <!-- Nombre -->
                        <td class="px-6 py-4 text-white font-medium">
                            {{ $user->name }}
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 text-gray-400">
                            {{ $user->email }}
                        </td>

                        <!-- Rol -->
                        <td class="px-6 py-4 text-gray-300">
                            {{ \App\Models\User::ROLE_LABELS[$user->role] ?? $user->role }}
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-500/10 text-green-400',
                                    'inactive' => 'bg-red-500/10 text-red-400',
                                    'pending' => 'bg-yellow-500/10 text-yellow-400',
                                ];
                            @endphp

                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $statusColors[$user->status] ?? 'bg-gray-500/10 text-gray-400' }}">
                                {{ \App\Models\User::STATUS_LABELS[$user->status] ?? $user->status }}
                            </span>
                        </td>

                        <!-- ACCIONES -->
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-4">

                                <!-- EDITAR -->
                                <a href="{{ route('users.edit', $user) }}"
                                   class="flex items-center gap-1 text-[#F59E0B]
                                          hover:text-[#FBBF24] text-sm transition">
                                    ✏️ Editar
                                </a>

                                <!-- ELIMINAR / DESACTIVAR -->
                                @if(auth()->id() === $user->id)
                                    <!-- No se puede eliminar a sí mismo -->
                                    <span class="flex items-center gap-1 text-gray-500
                                                 text-sm cursor-not-allowed"
                                          title="No podés desactivar tu propio usuario">
                                        🛑 Eliminar
                                    </span>
                                @else
                                    <div x-data>
                                        <form
                                            x-ref="deleteForm{{ $user->id }}"
                                            action="{{ route('users.destroy', $user) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="button"
                                                @click="$store.modal.show({
                                                    title: 'Desactivar usuario',
                                                    message: '¿Estás seguro de desactivar este usuario? No podrá acceder al sistema.',
                                                    onConfirm: () => $refs.deleteForm{{ $user->id }}.submit()
                                                })"
                                                class="flex items-center gap-1 text-red-500
                                                       hover:text-red-400 text-sm transition">
                                                🗑️ Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @endif

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5"
                            class="px-6 py-10 text-center text-gray-400">
                            No hay usuarios registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINACIÓN -->
    <div>
        {{ $users->links() }}
    </div>

</div>
@endsection
