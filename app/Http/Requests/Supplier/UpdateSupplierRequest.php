<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $supplierId = $this->route('supplier');

        return [
            'name' => ['required', 'string', 'max:255'],

            'tax_id' => [
                'nullable',
                'string',
                'max:20',
                'unique:suppliers,tax_id,' . $supplierId,
            ],

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
            'tax_id.unique' => 'El CUIT ya está asignado a otro proveedor.',
            'email.email' => 'El email no tiene un formato válido.',
        ];
    }
}
