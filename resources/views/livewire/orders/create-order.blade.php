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
        <h1 class="text-2xl font-semibold text-white">Nuevo pedido</h1>
        <p class="text-sm text-gray-400">
            Registrar pedido de cliente
        </p>
    </div>

    <div class="bg-[#111827] border border-[#1F2933] rounded-2xl p-6 space-y-8 shadow-lg">

        {{-- CLIENTE --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-300 mb-1">Cliente registrado</label>
                <select
                    wire:model="customer_id"
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white"
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
                <label class="block text-sm text-gray-300 mb-1">Nombre cliente</label>
                <input
                    type="text"
                    wire:model="customer_name"
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white"
                >
            </div>
        </div>

        {{-- TIPO / PAGO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <select wire:model="order_type" class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white">
                <option value="in_store">En local</option>
                <option value="delivery">Delivery</option>
            </select>

            <select wire:model="payment_method" class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white">
                <option value="cash">Efectivo</option>
                <option value="card">Tarjeta</option>
                <option value="qr">QR</option>
                <option value="other">Otro</option>
            </select>
        </div>

        {{-- DIRECCIÓN --}}
        @if($order_type === 'delivery')
            <div>
                <label class="block text-sm text-gray-300 mb-1">Dirección</label>
                <input
                    type="text"
                    wire:model="address"
                    class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-4 py-2 text-white"
                >
            </div>
        @endif

        {{-- PRODUCTOS --}}
        <div class="space-y-4">
            <h2 class="text-white font-semibold">Productos</h2>

            @foreach($products as $index => $item)
                <div class="grid grid-cols-12 gap-3 items-center">
                    <div class="col-span-6">
                        <select
                            wire:model="products.{{ $index }}.product_id"
                            class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white"
                        >
                            <option value="">Seleccionar producto</option>
                            @foreach($availableProducts as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-3">
                        <input
                            type="number"
                            min="1"
                            wire:model="products.{{ $index }}.quantity"
                            class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white"
                        >
                    </div>

                    <div class="col-span-2">
                        <input
                            type="number"
                            step="0.01"
                            wire:model="products.{{ $index }}.unit_price"
                            class="w-full bg-[#0B1220] border border-[#1F2933] rounded-lg px-3 py-2 text-white"
                        >
                    </div>

                    <div class="col-span-1 text-center">
                        <button
                            type="button"
                            wire:click="removeProduct({{ $index }})"
                            class="text-red-400 hover:text-red-300"
                        >
                            ✕
                        </button>
                    </div>
                </div>
            @endforeach

            <button
                type="button"
                wire:click="addProduct"
                class="text-sm text-[#F59E0B] hover:underline"
            >
                + Agregar producto
            </button>
        </div>

        {{-- TOTAL --}}
        <div class="text-right text-white font-semibold">
            Total: ${{ number_format($this->total, 2) }}
        </div>

        {{-- ACCIONES --}}
        <div class="flex justify-end gap-3 pt-4">
            <a
                href="{{ route('orders.index') }}"
                class="px-4 py-2 border border-[#1F2933] rounded-lg text-gray-300 hover:bg-[#0B1220]"
            >
                Cancelar
            </a>

            <button
                wire:click="save"
                class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-6 py-2 rounded-lg font-semibold"
            >
                Guardar pedido
            </button>
        </div>

    </div>
</div>
