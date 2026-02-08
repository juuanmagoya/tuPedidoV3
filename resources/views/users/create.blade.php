@extends('layouts.app')

@section('title', 'Nuevo usuario')

@section('content')
<div class="max-w-3xl space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-white">Nuevo usuario</h1>
        <p class="text-sm text-gray-400">
            Crear un nuevo usuario del sistema
        </p>
    </div>

    <div x-data>
        <form
            x-ref="form"
            method="POST"
            action="{{ route('users.store') }}"
            class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
        >
            @csrf

            <!-- Nombre -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Nombre</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B]"
                >
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B]"
                >
            </div>

            <!-- Rol -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Rol</label>
                <select
                    name="role"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B]"
                >
                    <option value="">Seleccionar rol</option>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" @selected(old('role') === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Contraseña</label>
                <input
                    type="password"
                    name="password"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white"
                >
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Confirmar contraseña</label>
                <input
                    type="password"
                    name="password_confirmation"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white"
                >
            </div>

            <!-- Info estado -->
            <div class="text-sm text-yellow-400 bg-[#0B1220] border border-[#1F2933] rounded-lg p-3">
                El usuario será creado con estado <strong>Pendiente</strong>.
            </div>

            <!-- Acciones -->
            <div class="flex justify-end gap-3">
                <a
                    href="{{ route('users.index') }}"
                    class="px-4 py-2 rounded-lg border border-[#1F2933] text-gray-300 hover:bg-[#0B1220]"
                >
                    Cancelar
                </a>

                <button
                    type="button"
                    @click="$store.modal.show({
                        title: 'Confirmar creación',
                        message: '¿Deseás crear este usuario?',
                        onConfirm: () => $refs.form.submit()
                    })"
                    class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-6 py-2 rounded-lg font-semibold"
                >
                    Guardar
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
