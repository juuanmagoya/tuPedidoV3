@extends('layouts.app')

@section('title', 'Editar usuario')

@section('content')
<div class="max-w-3xl space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-2xl font-semibold text-white">Editar usuario</h1>
        <p class="text-sm text-gray-400">
            Modificar datos y estado del usuario
        </p>
    </div>

    <div x-data>
        <form
            x-ref="form"
            method="POST"
            action="{{ route('users.update', $user) }}"
            class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
        >
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Nombre</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B]"
                >
            </div>

            <!-- Email (solo lectura) -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Email</label>
                <input
                    type="email"
                    value="{{ $user->email }}"
                    disabled
                    class="w-full bg-gray-800 border border-[#1F2933] rounded-lg px-4 py-2 text-gray-400"
                >
            </div>

            <!-- Rol -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Rol</label>
                <select
                    name="role"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white"
                >
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" @selected(old('role', $user->role) === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Estado -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Estado</label>
                <select
                    name="status"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white"
                >
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(old('status', $user->status) === $value)>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Advertencia si edita su propio usuario -->
            @if($user->id === auth()->id())
                <div class="text-sm text-yellow-400 bg-[#0B1220] border border-[#1F2933] rounded-lg p-3">
                    Estás editando tu propio usuario. No podés desactivar tu cuenta.
                </div>
            @endif

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
                        title: 'Confirmar cambios',
                        message: '¿Deseás guardar los cambios del usuario?',
                        onConfirm: () => $refs.form.submit()
                    })"
                    class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-6 py-2 rounded-lg font-semibold"
                >
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
