@extends('layouts.app')

@section('title', 'Editar compra')

@section('content')
<div class="max-w-5xl space-y-6">

    {{-- ERRORES --}}
    @if ($errors->any())
        <div
            id="alert-error"
            class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 transition-opacity duration-500"
        >
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-semibold text-white">Editar compra</h1>
        <p class="text-sm text-gray-400">
            Modificar compra registrada
        </p>
    </div>

    {{-- ALPINE --}}
    <div
        x-data="{
            items: {{ Js::from(
                $purchase->items->map(fn ($item) => [
                    'input_id' => $item->input_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'unit' => $item->unit,
                ])
            ) }}
        }"
    >

        <form
            x-ref="form"
            method="POST"
            action="{{ route('purchases.update', $purchase) }}"
            class="bg-[#111827] border border-[#1F2933]
                   rounded-2xl p-6 space-y-8 shadow-lg"
        >
            @csrf
            @method('PUT')

            {{-- DATOS GENERALES --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Proveedor --}}
                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Proveedor
                    </label>
                    <select
                        name="supplier_id"
                        required
                        class="w-full bg-[#0B1220] border border-[#1F2933]
                               rounded-lg px-3 py-2 text-white"
                    >
                        @foreach($suppliers as $supplier)
                            <option
                                value="{{ $supplier->id }}"
                                @selected($supplier->id === $purchase->supplier_id)
                            >
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Fecha --}}
                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Fecha de compra
                    </label>
                    <input
                        type="date"
                        name="purchase_date"
                        required
                        value="{{ $purchase->purchase_date->toDateString() }}"
                        class="w-full bg-[#0B1220] border border-[#1F2933]
                               rounded-lg px-4 py-2 text-white"
                        style="color-scheme: dark;"
                    >
                </div>
            </div>

            {{-- NOTAS --}}
            <div>
                <label class="block text-sm text-gray-300 mb-1">
                    Notas
                </label>
                <textarea
                    name="notes"
                    rows="3"
                    class="w-full bg-[#0B1220] border border-[#1F2933]
                           rounded-lg px-4 py-2 text-white"
                >{{ $purchase->notes }}</textarea>
            </div>

            {{-- ITEMS --}}
            <div class="space-y-4">
                <h2 class="text-white font-semibold">
                    Insumos comprados
                </h2>

                <template x-for="(row, index) in items" :key="index">
                    <div class="grid grid-cols-12 gap-3 items-center">

                        {{-- Insumo --}}
                        <div class="col-span-6">
                            <select
                                :name="`items[${index}][input_id]`"
                                x-model="row.input_id"
                                @change="row.unit = $event.target.selectedOptions[0].dataset.unit"
                                required
                                class="w-full bg-[#0B1220] border border-[#1F2933]
                                       rounded-lg px-3 py-2 text-white"
                            >
                                @foreach($inputs as $input)
                                    <option
                                        value="{{ $input->id }}"
                                        data-unit="{{ $input->unit }}"
                                    >
                                        {{ $input->name }} ({{ $input->unit }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" :name="`items[${index}][unit]`" x-model="row.unit">

                        {{-- Cantidad --}}
                        <div class="col-span-3">
                            <input
                                type="number"
                                step="0.001"
                                min="0.001"
                                required
                                x-model="row.quantity"
                                :name="`items[${index}][quantity]`"
                                class="w-full bg-[#0B1220] border border-[#1F2933]
                                       rounded-lg px-3 py-2 text-white"
                            >
                        </div>

                        {{-- Precio --}}
                        <div class="col-span-2">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                required
                                x-model="row.unit_price"
                                :name="`items[${index}][unit_price]`"
                                class="w-full bg-[#0B1220] border border-[#1F2933]
                                       rounded-lg px-3 py-2 text-white"
                            >
                        </div>

                        {{-- Eliminar --}}
                        <div class="col-span-1 text-center">
                            <button
                                type="button"
                                @click="items.length > 1 && items.splice(index, 1)"
                                class="text-red-400 hover:text-red-300"
                                title="Eliminar"
                            >
                                ✕
                            </button>
                        </div>
                    </div>
                </template>

                <button
                    type="button"
                    @click="items.push({})"
                    class="text-sm text-[#F59E0B] hover:underline"
                >
                    + Agregar insumo
                </button>
            </div>

            {{-- ACCIONES --}}
            <div class="flex justify-end gap-3 pt-4">
                <a
                    href="{{ route('purchases.show', $purchase) }}"
                    class="px-4 py-2 border border-[#1F2933]
                           rounded-lg text-gray-300 hover:bg-[#0B1220]"
                >
                    Cancelar
                </a>

                <button
                    type="button"
                    @click="$store.modal.show({
                        title: 'Confirmar actualización',
                        message: '¿Deseás guardar los cambios de esta compra?',
                        onConfirm: () => $refs.form.submit()
                    })"
                    class="bg-[#F59E0B] hover:bg-[#FBBF24]
                        text-black px-6 py-2 rounded-lg font-semibold"
                >
                    Actualizar compra
                </button>

            </div>

        </form>
    </div>
</div>
@endsection
