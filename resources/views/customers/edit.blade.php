@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('content')
<div class="max-w-3xl space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-white">Actualizar cliente</h1>
        <p class="text-sm text-gray-400">
            Actualizar datos de un cliente existente
        </p>
    </div>

    <div x-data>

        <form
            x-ref="form"
            method="POST"
            action="{{ route('customers.update', $customer->id) }}"
            class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
        >
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">
                    Nombre
                </label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $customer->name) }}"
                    required
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B] focus:outline-none"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">
                    Email
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', $customer->email) }}"
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B] focus:outline-none"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">
                    Teléfono
                </label>
                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone', $customer->phone) }}"
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B] focus:outline-none"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">
                    Dirección
                </label>
                <input
                    type="text"
                    name="address"
                    value="{{ old('address', $customer->address) }}"
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B] focus:outline-none"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1">
                    Notas 
                </label>
                <textarea
                    name="notes"
                    rows="3"
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white
                           focus:ring-2 focus:ring-[#F59E0B] focus:outline-none"
                >{{ old('notes', $customer->notes) }}</textarea>
            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('customers.index') }}"
                    class="px-4 py-2 rounded-lg border border-[#1F2933] text-gray-300
                           hover:bg-[#0B1220] transition"
                >
                    Cancelar
                </a>

                <button
                    type="button"
                    @click="$store.modal.show({
                        title: 'Confirmar actualización',
                        message: '¿Deseás actualizar este cliente?',
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