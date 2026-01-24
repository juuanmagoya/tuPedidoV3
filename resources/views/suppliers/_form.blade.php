@csrf

<div class="grid grid-cols-2 gap-6">

    <!-- Nombre -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Nombre</label>
        <input type="text" name="name"
               value="{{ old('name', $supplier->name ?? '') }}"
               class="w-full rounded-lg p-2 bg-[#1F2933] text-white">
        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- CUIT -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">CUIT</label>
        <input type="text" name="tax_id"
               value="{{ old('tax_id', $supplier->tax_id ?? '') }}"
               class="w-full rounded-lg p-2 bg-[#1F2933] text-white">
        @error('tax_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Teléfono -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Teléfono</label>
        <input type="text" name="phone"
               value="{{ old('phone', $supplier->phone ?? '') }}"
               class="w-full rounded-lg p-2 bg-[#1F2933] text-white">
    </div>

    <!-- Email -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Email</label>
        <input type="email" name="email"
               value="{{ old('email', $supplier->email ?? '') }}"
               class="w-full rounded-lg p-2 bg-[#1F2933] text-white">
        @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <!-- Dirección -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Dirección</label>
        <input type="text" name="address"
               value="{{ old('address', $supplier->address ?? '') }}"
               class="w-full rounded-lg p-2 bg-[#1F2933] text-white">
    </div>

    <!-- Contacto -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Contacto</label>
        <input type="text" name="contact_name"
               value="{{ old('contact_name', $supplier->contact_name ?? '') }}"
               class="w-full rounded-lg p-2 bg-[#1F2933] text-white">
    </div>

    <!-- Condición de pago -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Condición de pago</label>
        <input type="text" name="payment_terms"
               placeholder="Ej: 30 días, contado"
               value="{{ old('payment_terms', $supplier->payment_terms ?? '') }}"
               class="w-full rounded-lg p-2 bg-[#1F2933] text-white">
    </div>
    <!-- =========================
    Estado
    ========================= -->
    <div>
        <label class="block text-sm text-gray-300 mb-1">Estado</label>
        <select name="is_active"
                class="w-full border rounded-lg p-2 bg-[#1F2933] text-white
                    focus:ring-[#F59E0B]">
            <option value="1"
                @selected(old('is_active', $supplier->is_active ?? 1) == 1)>
                Activo
            </option>
            <option value="0"
                @selected(old('is_active', $supplier->is_active ?? 1) == 0)>
                Inactivo
            </option>
        </select>

        @error('is_active')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>


    <!-- Observaciones -->
    <div class="col-span-2">
        <label class="block text-sm text-gray-300 mb-1">Observaciones</label>
        <textarea name="notes"
                  rows="3"
                  class="w-full rounded-lg p-2 bg-[#1F2933] text-white">{{ old('notes', $supplier->notes ?? '') }}</textarea>
    </div>

</div>

<!-- BOTONES -->
<div class="mt-6 flex justify-between">
    <a href="{{ route('suppliers.index') }}"
       class="px-4 py-2 rounded-lg border border-[#1F2933]
              text-gray-300 hover:bg-[#0B1220] transition">
        Cancelar
    </a>

    @php $isEdit = isset($supplier); @endphp

    <button type="button"
            @click="$store.modal.show({
                title: '{{ $isEdit ? 'Confirmar actualización' : 'Confirmar creación' }}',
                message: '¿Deseás {{ $isEdit ? 'actualizar' : 'registrar' }} este proveedor?',
                onConfirm: () => $refs.form.submit()
            })"
            class="bg-[#F59E0B] hover:bg-[#FBBF24]
                   text-black px-6 py-2 rounded-lg font-semibold">
        {{ $isEdit ? 'Actualizar' : 'Registrar' }}
    </button>
</div>
