@props(['products'])

@if($products->isNotEmpty())
<div class="bg-red-600 text-white rounded-xl p-4 mb-6 shadow flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="font-semibold text-lg">¡Productos con bajo stock!</h2>
        <p class="text-sm opacity-90">
            Hay {{ $products->count() }} producto(s) con stock menor o igual al mínimo.
        </p>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($products as $product)
            <span class="bg-red-500 px-3 py-1 rounded-full text-sm font-medium">
                {{ $product->name }} ({{ $product->stock }})
            </span>
        @endforeach
    </div>
        <!-- Botón cerrar -->
    <button @click="open = false" class="ml-auto text-white font-bold"></button>
</div>
@endif
