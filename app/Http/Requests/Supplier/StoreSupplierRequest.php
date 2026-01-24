<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // luego podés meter policies
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'tax_id' => ['nullable', 'string', 'max:20', 'unique:suppliers,tax_id'],

            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],

            'contact_name' => ['nullable', 'string', 'max:255'],
            'payment_terms' => ['nullable', 'string', 'max:100'],

            'notes' => ['nullable', 'string'],

            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del proveedor es obligatorio.',
            'tax_id.unique' => 'Ya existe un proveedor con ese CUIT.',
            'email.email' => 'El email no tiene un formato válido.',
        ];
    }
}
