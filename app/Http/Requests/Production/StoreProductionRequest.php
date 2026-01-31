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

            // Insumos consumidos
            'inputs' => ['required', 'array', 'min:1'],
            'inputs.*.inputs_id' => ['required', 'exists:inputs,id'],
            'inputs.*.quantity'  => ['required', 'numeric', 'min:0.001'],


            // Productos generados
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'numeric', 'min:0.001'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'La fecha de producción es obligatoria',

            'inputs.required' => 'Debe registrar al menos un insumo consumido',
            'inputs.array' => 'Formato inválido de insumos',
            'inputs.min' => 'Debe registrar al menos un insumo',

            'inputs.*.inputs_id.required' => 'Debe seleccionar un insumo',
            'inputs.*.inputs_id.exists' => 'El insumo seleccionado no existe',
            'inputs.*.quantity.required' => 'Debe indicar la cantidad consumida',
            'inputs.*.quantity.min' => 'La cantidad del insumo debe ser mayor a 0',

            'products.required' => 'Debe registrar al menos un producto generado',
            'products.array' => 'Formato inválido de productos',
            'products.min' => 'Debe registrar al menos un producto',

            'products.*.product_id.required' => 'Debe seleccionar un producto',
            'products.*.product_id.exists' => 'El producto seleccionado no existe',
            'products.*.quantity.required' => 'Debe indicar la cantidad producida',
            'products.*.quantity.min' => 'La cantidad producida debe ser mayor a 0',
        ];
    }

}
