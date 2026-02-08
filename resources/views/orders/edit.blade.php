@extends('layouts.app')

@section('title', 'Editar pedido')

@section('content')
<div class="max-w-5xl space-y-6">

    {{-- ERRORES --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-semibold text-white">
            Editar pedido #{{ $order->id }}
        </h1>
        <p class="text-sm text-gray-400">
            Modificar información del pedido
        </p>
    </div>

    {{-- ALPINE --}}
    <div x-data="orderEdit()" x-init="init()">

        <form
            x-ref="form"
            method="POST"
            action="{{ route('orders.update', $order) }}"
            class="bg-[#111827] border border-[#1F2933]
                   rounded-2xl p-6 space-y-8 shadow-lg"
        >
            @csrf
            @method('PUT')

            {{-- STATUS --}}
            <input type="hidden" name="status" value="{{ $order->status }}">

            {{-- ================= CLIENTE ================= --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Cliente registrado
                    </label>
                    <select
                        name="customer_id"
                        x-model="selectedCustomerId"
                        @change="onCustomerChange()"
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

                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Nombre del cliente
                    </label>

                    <input
                        type="text"
                        x-model="customerName"
                        :disabled="selectedCustomerId"
                        class="w-full bg-[#0B1220] border border-[#1F2933]
                               rounded-lg px-4 py-2 text-white disabled:opacity-60"
                    >

                    <input type="hidden" name="customer_name" :value="customerName">
                </div>
            </div>

            {{-- ================= PEDIDO ================= --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Tipo de pedido
                    </label>
                    <select
                        name="order_type"
                        x-model="orderType"
                        @change="onOrderTypeChange()"
                        class="w-full bg-[#0B1220] border border-[#1F2933]
                               rounded-lg px-3 py-2 text-white"
                    >
                        <option value="in_store">En local</option>
                        <option value="delivery">Delivery</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-gray-300 mb-1">
                        Método de pago
                    </label>
                    <select
                        name="payment_method"
                        class="w-full bg-[#0B1220] border border-[#1F2933]
                               rounded-lg px-3 py-2 text-white"
                    >
                        <option value="cash" @selected($order->payment_method === 'cash')>Efectivo</option>
                        <option value="card" @selected($order->payment_method === 'card')>Tarjeta</option>
                        <option value="qr" @selected($order->payment_method === 'qr')>QR</option>
                        <option value="other" @selected($order->payment_method === 'other')>Otro</option>
                    </select>
                </div>
            </div>

            {{-- Dirección --}}
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

            {{-- ================= PRODUCTOS ================= --}}
            <div class="space-y-4">
                <h2 class="text-white font-semibold">Productos</h2>

                <template x-for="(row, index) in items" :key="index">
                    <div class="grid grid-cols-12 gap-3 items-center">

                        <div class="col-span-6">
                            <select
                                :name="`products[${index}][product_id]`"
                                x-model="row.product_id"
                                @change="setPrice(row, $event)"
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
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-span-3">
                            <input
                                type="number"
                                min="1"
                                x-model.number="row.quantity"
                                :name="`products[${index}][quantity]`"
                                required
                                class="w-full bg-[#0B1220] border border-[#1F2933]
                                       rounded-lg px-3 py-2 text-white"
                            >
                        </div>

                        <div class="col-span-2">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                x-model.number="row.unit_price"
                                :name="`products[${index}][unit_price]`"
                                required
                                class="w-full bg-[#0B1220] border border-[#1F2933]
                                       rounded-lg px-3 py-2 text-white"
                            >
                        </div>

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
                    @click="addItem()"
                    class="text-sm text-[#F59E0B] hover:underline"
                >
                    + Agregar producto
                </button>
            </div>

            {{-- ================= ACCIONES ================= --}}
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
                    @click="showConfirmModal = true"
                    class="bg-[#F59E0B] hover:bg-[#FBBF24]
                           text-black px-6 py-2 rounded-lg font-semibold"
                >
                    Guardar cambios
                </button>
            </div>

        </form>

        {{-- ================= MODAL ================= --}}
        <div
            x-show="showConfirmModal"
            x-transition
            class="fixed inset-0 z-50 flex items-center justify-center"
        >
            <div
                class="absolute inset-0 bg-black/60"
                @click="showConfirmModal = false"
            ></div>

            <div
                class="relative bg-[#0B1220] border border-[#1F2933]
                       rounded-xl p-6 w-full max-w-md shadow-xl"
            >
                <h2 class="text-lg font-semibold text-white mb-2">
                    Confirmar actualización
                </h2>

                <p class="text-sm text-gray-400 mb-6">
                    ¿Estás seguro de que querés guardar los cambios del pedido?
                </p>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        @click="showConfirmModal = false"
                        class="px-4 py-2 border border-[#1F2933]
                               rounded-lg text-gray-300 hover:bg-[#111827]"
                    >
                        Cancelar
                    </button>

                    <button
                        type="button"
                        @click="$refs.form.submit()"
                        class="bg-[#F59E0B] hover:bg-[#FBBF24]
                               text-black px-4 py-2 rounded-lg font-semibold"
                    >
                        Sí, actualizar
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ================= ALPINE SCRIPT ================= --}}
<script>
function orderEdit() {
    return {
        showConfirmModal: false,
        orderType: @js($order->order_type),
        selectedCustomerId: @js($order->customer_id),
        customerName: @js($order->customer_name ?? ''),
        address: @js($order->address ?? ''),

        customers: @js($customers->map(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'address' => $c->address,
        ])),

        items: @js(
            $order->products->count()
                ? $order->products->map(fn ($i) => [
                    'product_id' => $i->product_id,
                    'quantity' => $i->quantity,
                    'unit_price' => $i->unit_price,
                ])
                : [[
                    'product_id' => '',
                    'quantity' => 1,
                    'unit_price' => 0,
                ]]
        ),

        init() {
            this.onCustomerChange();
        },

        onCustomerChange() {
            const customer = this.customers.find(c => c.id == this.selectedCustomerId);
            if (customer) {
                this.customerName = customer.name;
                if (this.orderType === 'delivery') {
                    this.address = customer.address ?? '';
                }
            }
        },

        onOrderTypeChange() {
            if (this.orderType === 'delivery') {
                this.onCustomerChange();
            }
        },

        addItem() {
            this.items.push({
                product_id: '',
                quantity: 1,
                unit_price: 0,
            });
        },

        setPrice(row, event) {
            const price = event.target.selectedOptions[0]?.dataset.price;
            if (price !== undefined) {
                row.unit_price = parseFloat(price);
            }
        }
    }
}
</script>
@endsection
