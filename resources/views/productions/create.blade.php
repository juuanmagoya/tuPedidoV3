@extends('layouts.app')

@section('title', 'Nueva Producción')

@section('content')
<div class="max-w-5xl space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-white">Nueva producción</h1>
        <p class="text-sm text-gray-400">
            Registrar una nueva producción
        </p>
    </div>

    <div x-data="{ inputs: [{}], products: [{}] }">

        <form
            x-ref="form"
            method="POST"
            action="{{ route('productions.store') }}"
            class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-6 shadow-lg"
        >
            @csrf

            <!-- Fecha -->
            <div>
                <label class="block text-sm text-gray-300 mb-1">Fecha</label>
                <input type="date"
                       name="date"
                       required
                       class="w-full bg-[#0B1220] border border-[#1F2933]
                              rounded-lg px-4 py-2 text-white">
            </div>

            <!-- ======================
                 INSUMOS
                 ====================== -->
            <div class="space-y-3">
                <h2 class="text-white font-semibold">Insumos consumidos</h2>

                <template x-for="(row, index) in inputs" :key="index">
                    <div class="grid grid-cols-3 gap-3">
                        <select :name="`inputs[${index}][input_id]`"
                                class="bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white">
                            <option value="">Insumo</option>
                            @foreach($inputs as $input)
                                <option value="{{ $input->id }}">
                                    {{ $input->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="number"
                               step="0.001"
                               :name="`inputs[${index}][quantity]`"
                               placeholder="Cantidad"
                               class="bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white">

                        <button type="button"
                                @click="inputs.splice(index,1)"
                                class="text-red-400">
                            ✕
                        </button>
                    </div>
                </template>

                <button type="button"
                        @click="inputs.push({})"
                        class="text-sm text-[#F59E0B]">
                    + Agregar insumo
                </button>
            </div>

            <!-- ======================
                 PRODUCTOS
                 ====================== -->
            <div class="space-y-3">
                <h2 class="text-white font-semibold">Productos generados</h2>

                <template x-for="(row, index) in products" :key="index">
                    <div class="grid grid-cols-3 gap-3">
                        <select :name="`products[${index}][product_id]`"
                                class="bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white">
                            <option value="">Producto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>

                        <input type="number"
                               step="0.001"
                               :name="`products[${index}][quantity]`"
                               placeholder="Cantidad"
                               class="bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white">

                        <button type="button"
                                @click="products.splice(index,1)"
                                class="text-red-400">
                            ✕
                        </button>
                    </div>
                </template>

                <button type="button"
                        @click="products.push({})"
                        class="text-sm text-[#F59E0B]">
                    + Agregar producto
                </button>
            </div>

            <!-- ACCIONES -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('productions.index') }}"
                   class="px-4 py-2 border border-[#1F2933] rounded-lg text-gray-300">
                    Cancelar
                </a>

                <button type="button"
                        @click="$store.modal.show({
                            title: 'Confirmar producción',
                            message: '¿Deseás registrar esta producción?',
                            onConfirm: () => $refs.form.submit()
                        })"
                        class="bg-[#F59E0B] hover:bg-[#FBBF24]
                               text-black px-6 py-2 rounded-lg font-semibold">
                    Guardar
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
