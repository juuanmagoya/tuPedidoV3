@csrf

<div class="grid grid-cols-2 gap-6">

    <!-- Nombre -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Nombre</label>
        <input type="text" name="name"
               value="{{ old('name', $input->name ?? '') }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white">
        @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Unidad -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Unidad</label>
        <input type="text" name="unit"
               placeholder="kg, gr, lts"
               value="{{ old('unit', $input->unit ?? '') }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white">
        @error('unit')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Stock -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Stock</label>
        <input type="number" step="0.01" name="stock"
               value="{{ old('stock', $input->stock ?? 0) }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white">
        @error('stock')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Stock mínimo -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Stock mínimo</label>
        <input type="number" step="0.01" name="min_stock"
               value="{{ old('min_stock', $input->min_stock ?? '') }}"
               class="w-full border rounded-lg p-2 bg-[#1F2933] text-white">
        @error('min_stock')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <!-- Estado -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Estado</label>
        <select name="is_active"
                class="w-full border rounded-lg p-2 bg-[#1F2933] text-white">
            <option value="1" @selected(old('is_active', $input->is_active ?? 1) == 1)>Activo</option>
            <option value="0" @selected(old('is_active', $input->is_active ?? 1) == 0)>Inactivo</option>
        </select>
    </div>

    <!-- Notas -->
    <div class="col-span-2">
        <label class="block text-sm text-gray-300 mb-1">Notas</label>
        <textarea name="notes" rows="3"
                  class="w-full border rounded-lg p-2 bg-[#1F2933] text-white">{{ old('notes', $input->notes ?? '') }}</textarea>
    </div>

</div>

@php $isEdit = isset($input); @endphp

<div class="mt-6 flex justify-between">
    <a href="{{ route('inputs.index') }}"
       class="px-4 py-2 rounded-lg border border-[#1F2933] text-gray-300 hover:bg-[#0B1220]">
        Cancelar
    </a>

    <button
        type="button"
        @click="$store.modal.show({
            title: '{{ $isEdit ? 'Confirmar actualización' : 'Confirmar creación' }}',
            message: '¿Deseás {{ $isEdit ? 'actualizar' : 'registrar' }} este insumo?',
            onConfirm: () => $refs.form.submit()
        })"
        class="bg-[#F59E0B] hover:bg-[#FBBF24] text-black px-6 py-2 rounded-lg font-semibold"
    >
        {{ $isEdit ? 'Actualizar' : 'Registrar' }}
    </button>
</div>
