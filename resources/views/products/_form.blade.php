@csrf

<div class="grid grid-cols-2 gap-6">

    <!-- =========================
         Nombre del producto
         ========================= -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Nombre</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white focus:ring-[#F59E0B]">
        @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- =========================
         SKU
         ========================= -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">SKU</label>
        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white focus:ring-[#F59E0B]">
        @error('sku')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- =========================
         Precio
         ========================= -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Precio</label>
        <input type="number" step="0.01" name="price"
               value="{{ old('price', $product->price ?? '') }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white focus:ring-[#F59E0B]">
        @error('price')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- =========================
         Precio costo
         ========================= -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Precio costo</label>
        <input type="number" step="0.01" name="cost_price"
               value="{{ old('cost_price', $product->cost_price ?? '') }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white focus:ring-[#F59E0B]">
        @error('cost_price')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- =========================
         Stock
         ========================= -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Stock</label>
        <input type="number" name="stock"
               value="{{ old('stock', $product->stock ?? 0) }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white focus:ring-[#F59E0B]">
        @error('stock')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- =========================
         Stock mínimo
         ========================= -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Stock mínimo</label>
        <input type="number" name="min_stock"
               value="{{ old('min_stock', $product->min_stock ?? 0) }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white focus:ring-[#F59E0B]">
        @error('min_stock')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- =========================
         Categoría
         ========================= -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Categoría</label>
        <select name="category_id" class="w-full border rounded-lg p-2 bg-[#1F2933] text-white focus:ring-[#F59E0B]">
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    @selected(old('category_id', $product->category_id ?? '') == $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- =========================
         Estado
         ========================= -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Estado</label>
        <select name="status" class="w-full border rounded-lg p-2 bg-[#1F2933] text-white focus:ring-[#F59E0B]">
            <option value="1" @selected(old('status', $product->status ?? 1) == 1)>Activo</option>
            <option value="0" @selected(old('status', $product->status ?? 1) == 0)>Inactivo</option>
        </select>
        @error('status')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- =========================
         Imagen con preview
         ========================= -->
    <div x-data="imagePreview()" class="col-span-2 space-y-2">
        <label class="block text-sm text-gray-300 mb-1">Imagen</label>
        <input type="file" accept="image/*"
               x-on:change="previewImage($event)"
               name="image"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white focus:ring-[#F59E0B]">
        
        <!-- Preview -->
        <template x-if="imageUrl">
            <img :src="imageUrl" class="h-32 w-32 object-cover rounded-lg mt-2">
        </template>

        @if(isset($product) && $product->image)
            <img src="{{ asset('storage/'.$product->image) }}"
                 class="h-32 w-32 object-cover rounded-lg mt-2"
                 x-show="!imageUrl">
        @endif

        @error('image')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

</div>

<!-- =========================
     Botón Guardar
     ========================= -->
<div class="mt-6">
    <a
        href="{{ route('products.index') }}"
        class="px-4 py-2 rounded-lg border border-[#1F2933] text-gray-300
                hover:bg-[#0B1220] transition"
    >
                    Cancelar
    </a>
@php
    $isEdit = isset($product);
@endphp

    <button
        type="button"
        @click="$store.modal.show({
            title: '{{ $isEdit ? 'Confirmar actualización' : 'Confirmar creación' }}',
            message: '¿Deseás {{ $isEdit ? 'actualizar' : 'registrar' }} este producto?',
            onConfirm: () => $refs.form.submit()
        })"
        class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-6 py-2 rounded-lg font-semibold"
    >
        {{ $isEdit ? 'Actualizar' : 'Registrar' }}
    </button>
</div>

<!-- =========================
     Alpine.js para preview
     ========================= -->
<script>
function imagePreview() {
    return {
        imageUrl: null,
        previewImage(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = e => this.imageUrl = e.target.result;
            reader.readAsDataURL(file);
        }
    }
}
</script>
