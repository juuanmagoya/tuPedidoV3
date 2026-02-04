<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id'            => ['required', 'exists:suppliers,id'],
            'purchase_date'          => ['required', 'date'],
            'items'                  => ['required', 'array', 'min:1'],

            'items.*.input_id'       => ['required', 'exists:inputs,id'],
            'items.*.unit'           => ['required', 'string', 'max:20'],
            'items.*.quantity'       => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_price'     => ['required', 'numeric', 'min:0'],
        ];
    }

    

    public function messages(): array
    {
        return [
            'supplier_id.required'        => 'El proveedor es obligatorio.',
            'supplier_id.exists'          => 'El proveedor seleccionado no existe.',

            'purchase_date.required'      => 'La fecha de compra es obligatoria.',
            'purchase_date.date'          => 'La fecha de compra no es válida.',

            'items.*.unit.required'       => 'La unidad es obligatoria.',

            'items.required'              => 'Debe agregar al menos un insumo.',
            'items.array'                 => 'El formato de los insumos no es válido.',
            'items.min'                   => 'Debe agregar al menos un insumo.',

            'items.*.input_id.required'   => 'El insumo es obligatorio.',
            'items.*.input_id.exists'     => 'Uno de los insumos no existe.',

            'items.*.quantity.required'   => 'La cantidad es obligatoria.',
            'items.*.quantity.numeric'    => 'La cantidad debe ser numérica.',
            'items.*.quantity.min'        => 'La cantidad debe ser mayor a cero.',

            'items.*.unit_price.required' => 'El precio unitario es obligatorio.',
            'items.*.unit_price.numeric'  => 'El precio unitario debe ser numérico.',
            'items.*.unit_price.min'      => 'El precio unitario no puede ser negativo.',
        ];
    }
}
