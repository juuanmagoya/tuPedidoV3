@extends('layouts.app')

@section('title', 'Nuevo pedido')

@section('content')
<div class="max-w-5xl space-y-6">

    {{-- ERRORES --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-semibold text-white">Nuevo pedido</h1>
        <p class="text-sm text-gray-400">
            Registrar pedido
        </p>
    </div>

    {{-- ALPINE --}}
    <div
        x-data="{
            orderType: 'in_store',
            selectedCustomerId: '',
            customers: @js($customers->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'address' => $c->address,
            ])),
            customerName: '',
            address: '',
            items: [{}],

            selectCustomer(id) {
                const customer = this.customers.find(c => c.id == id);

                if (customer) {
                    this.customerName = customer.name;
                    if (this.orderType === 'delivery') {
                        this.address = customer.address ?? '';
                    }
                } else {
                    this.customerName = '';
                    this.address = '';
                }
            }
        }"
    >

        <form
            x-ref="form"
            method="POST"
            action="{{ route('orders.store') }}"
            class="bg-[#111827] border border-[#1F2933]
                   rounded-2xl p-6 space-y-8 shadow-lg"
        >
            @csrf

            {{-- CLIENTE --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Cliente registrado --}}
                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Cliente registrado
                    </label>
                    <select
                        name="customer_id"
                        x-model="selectedCustomerId"
                        @change="selectCustomer(selectedCustomerId)"
                        class="w-full bg-[#0B1220] border border-[#1F2933]
                               rounded-lg px-3 py-2 text-white"
                    >
                        <option value="">Cliente ocasional</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Estado --}}
                <input type="hidden" name="status" value="received">

                {{-- Nombre --}}
                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Nombre del cliente
                    </label>
                    <input
                        type="text"
                        name="customer_name"
                        x-model="customerName"
                        :disabled="selectedCustomerId !== ''"
                        required
                        class="w-full bg-[#0B1220] border border-[#1F2933]
                               rounded-lg px-4 py-2 text-white disabled:opacity-60"
                    >
                </div>
            </div>

            {{-- PEDIDO --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Tipo --}}
                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Tipo de pedido
                    </label>
                    <select
                        name="order_type"
                        x-model="orderType"
                        @change="selectCustomer(selectedCustomerId)"
                        class="w-full bg-[#0B1220] border border-[#1F2933]
                               rounded-lg px-3 py-2 text-white"
                    >
                        <option value="in_store">En local</option>
                        <option value="delivery">Delivery</option>
                    </select>
                </div>

                {{-- Pago --}}
                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Método de pago
                    </label>
                    <select
                        name="payment_method"
                        class="w-full bg-[#0B1220] border border-[#1F2933]
                               rounded-lg px-3 py-2 text-white"
                    >
                        <option value="cash">Efectivo</option>
                        <option value="card">Tarjeta</option>
                        <option value="qr">QR</option>
                        <option value="other">Otro</option>
                    </select>
                </div>
            </div>

            {{-- DIRECCIÓN --}}
            <div x-show="orderType === 'delivery'" x-transition>
                <label class="block text-sm text-gray-300 mb-1">
                    Dirección
                </label>
                <input
                    type="text"
                    name="address"
                    x-model="address"
                    class="w-full bg-[#0B1220] border border-[#1F2933]
                           rounded-lg px-4 py-2 text-white"
                >
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
                ></textarea>
            </div>

            {{-- PRODUCTOS --}}
            <div class="space-y-4">
                <h2 class="text-white font-semibold">Productos</h2>

                <template x-for="(row, index) in items" :key="index">
                    <div class="grid grid-cols-12 gap-3 items-center">

                        {{-- Producto --}}
                        <div class="col-span-6">
                            <select
                                :name="`products[${index}][product_id]`"
                                @change="
                                    const price = $event.target.selectedOptions[0].dataset.price;
                                    row.unit_price = price;
                                "
                                required
                                class="w-full bg-[#0B1220] border border-[#1F2933]
                                       rounded-lg px-3 py-2 text-white"
                            >
                                <option value="">Seleccionar producto</option>
                                @foreach($products as $product)
                                    <option
                                        value="{{ $product->id }}"
                                        data-price="{{ $product->price }}"
                                    >
                                        {{ $product->name }} -- stock:
                                        {{ $product ->stock}}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Cantidad --}}
                        <div class="col-span-3">
                            <input
                                type="number"
                                min="1"
                                required
                                placeholder="Cantidad"
                                :name="`products[${index}][quantity]`"
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
                                x-model="row.unit_price"
                                required
                                placeholder="Precio"
                                :name="`products[${index}][unit_price]`"
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
                    + Agregar producto
                </button>
            </div>

            {{-- ACCIONES --}}
            <div class="flex justify-end gap-3 pt-4">
                <a
                    href="{{ route('orders.index') }}"
                    class="px-4 py-2 border border-[#1F2933]
                           rounded-lg text-gray-300 hover:bg-[#0B1220]"
                >
                    Cancelar
                </a>

                <button
                    type="button"
                    @click="$store.modal.show({
                        title: 'Confirmar pedido',
                        message: '¿Deseás registrar este pedido?',
                        onConfirm: () => $refs.form.submit()
                    })"
                    class="bg-[#F59E0B] hover:bg-[#FBBF24]
                           text-black px-6 py-2 rounded-lg font-semibold"
                >
                    Guardar pedido
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
