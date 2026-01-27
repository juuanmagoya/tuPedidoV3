<?php

namespace App\Http\Requests\Production;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],

            // Inputs consumidos
            'inputs' => ['required', 'array', 'min:1'],
            'inputs.*.input_id' => ['required', 'exists:inputs,id'],
            'inputs.*.quantity' => ['required', 'numeric', 'min:0.001'],

            // Productos generados
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'numeric', 'min:0.001'],
        ];
    }

    public function messages(): array
    {
        return [
            'inputs.required' => 'Debe registrar al menos un insumo consumido',
            'products.required' => 'Debe registrar al menos un producto generado',
        ];
    }
}
