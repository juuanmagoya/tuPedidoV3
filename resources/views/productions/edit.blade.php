@extends('layouts.app')

@section('title', 'Actualizar Producción')

@section('content')

<div class="max-w-5xl space-y-6">

    {{-- Errores --}}
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

        <script>
            setTimeout(() => {
                const alert = document.getElementById('alert-error');
                if (alert) {
                    alert.classList.add('opacity-0');
                    setTimeout(() => alert.remove(), 500);
                }
            }, 2000);
        </script>
    @endif

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-semibold text-white">Actualizar producción</h1>
        <p class="text-sm text-gray-400">
            Modificá los datos de una producción existente
        </p>
    </div>

    {{-- Formulario --}}
    <form
        x-data="{
            inputs: {{ old('inputs')
                ? json_encode(old('inputs'))
                : json_encode(
                    $production->inputs->map(fn ($i) => [
                        'inputs_id' => $i->inputs_id,
                        'quantity' => $i->quantity_used,
                    ])
                )
            }},
            products: {{ old('products')
                ? json_encode(old('products'))
                : json_encode(
                    $production->products->map(fn ($p) => [
                        'product_id' => $p->product_id,
                        'quantity' => $p->quantity_produced,
                    ])
                )
            }},
        }"
        method="POST"
        action="{{ route('productions.update', $production->id) }}"
        class="bg-[#111827] border border-[#1F2933]
            rounded-2xl p-6 space-y-8 shadow-lg"
    >
        @csrf
        @method('PUT')

        {{-- FECHA --}}
        <div>
            <label class="block text-sm text-gray-300 mb-1">
                Fecha de producción
            </label>

            <input
                type="date"
                name="date"
                required
                value="{{ old('date', \Carbon\Carbon::parse($production->production_date)->format('Y-m-d')) }}"
                class="bg-[#0B1220] border border-[#1F2933]
                    rounded-lg px-4 py-2 text-white
                    focus:ring-2 focus:ring-[#F59E0B]"
                style="color-scheme: dark;"
            >
        </div>

        {{-- INSUMOS --}}
        <div class="space-y-4">
            <h2 class="text-white font-semibold">
                Insumos consumidos
            </h2>

            <template x-for="(row, index) in inputs" :key="index">
                <div class="grid grid-cols-12 gap-3 items-center">

                    <div class="col-span-7">
                        <select
                            :name="`inputs[${index}][inputs_id]`"
                            x-model="row.inputs_id"
                            required
                            class="w-full bg-[#0B1220] border border-[#1F2933]
                                rounded-lg px-3 py-2 text-white"
                        >
                            <option value="">Seleccionar insumo</option>
                            @foreach($inputs as $input)
                                <option value="{{ $input->id }}">
                                    {{ $input->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-4">
                        <input
                            type="number"
                            step="0.001"
                            min="0.001"
                            required
                            placeholder="Cantidad"
                            x-model="row.quantity"
                            :name="`inputs[${index}][quantity]`"
                            class="w-full bg-[#0B1220] border border-[#1F2933]
                                rounded-lg px-3 py-2 text-white"
                        >
                    </div>

                    <div class="col-span-1 text-center">
                        <button
                            type="button"
                            @click="inputs.length > 1 && inputs.splice(index, 1)"
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
                @click="inputs.push({ inputs_id: '', quantity: '' })"
                class="text-sm text-[#F59E0B] hover:underline"
            >
                + Agregar insumo
            </button>
        </div>

        {{-- PRODUCTOS --}}
        <div class="space-y-4">
            <h2 class="text-white font-semibold">
                Productos generados
            </h2>

            <template x-for="(row, index) in products" :key="index">
                <div class="grid grid-cols-12 gap-3 items-center">

                    <div class="col-span-7">
                        <select
                            :name="`products[${index}][product_id]`"
                            x-model="row.product_id"
                            required
                            class="w-full bg-[#0B1220] border border-[#1F2933]
                                rounded-lg px-3 py-2 text-white"
                        >
                            <option value="">Seleccionar producto</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-4">
                        <input
                            type="number"
                            step="0.001"
                            min="0.001"
                            required
                            placeholder="Cantidad"
                            x-model="row.quantity"
                            :name="`products[${index}][quantity]`"
                            class="w-full bg-[#0B1220] border border-[#1F2933]
                                rounded-lg px-3 py-2 text-white"
                        >
                    </div>

                    <div class="col-span-1 text-center">
                        <button
                            type="button"
                            @click="products.length > 1 && products.splice(index, 1)"
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
                @click="products.push({ product_id: '', quantity: '' })"
                class="text-sm text-[#F59E0B] hover:underline"
            >
                + Agregar producto
            </button>
        </div>

        {{-- ACCIONES --}}
        <div class="flex justify-end gap-3 pt-4">
            <a
                href="{{ route('productions.index') }}"
                class="px-4 py-2 border border-[#1F2933]
                    rounded-lg text-gray-300 hover:bg-[#0B1220]"
            >
                Cancelar
            </a>

            <button
                type="submit"
                class="bg-[#F59E0B] hover:bg-[#FBBF24]
                    text-black px-6 py-2 rounded-lg font-semibold"
            >
                Guardar cambios
            </button>
        </div>

    </form>

</div>
@endsection
