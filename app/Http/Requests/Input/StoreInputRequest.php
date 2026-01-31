<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;

class StoreInputRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[A-Za-z\s]+$/',
            'unit'       => 'required|string|max:50',
            'stock'      => 'required|numeric|min:1',
            'min_stock'  => 'nullable|numeric|min:1',
            'cost_price' => 'required|numeric|decimal:0,2|min:0',
            'is_active'  => 'required|boolean',
            'notes'      => 'nullable|string|max:500',
        ];
    }
    public function messages(): array
{
    return [
        'name.required'       => 'El nombre del insumo es obligatorio.',
        'name.string'         => 'El nombre del insumo debe ser un texto.',
        'name.max'            => 'El nombre del insumo no puede superar los 255 caracteres.',
        'name.regex'          => 'El nombre solo puede contener letras y espacios',

        'unit.required'       => 'La unidad es obligatoria.',
        'unit.string'         => 'La unidad debe ser un texto.',
        'unit.max'            => 'La unidad no puede superar los 50 caracteres.',

        'stock.required'      => 'El stock es obligatorio.',
        'stock.numeric'       => 'El stock debe ser un número.',
        'stock.min'           => 'El stock debe ser mayor o igual a 1.',

        'min_stock.numeric'   => 'El stock mínimo debe ser un número.',
        'min_stock.min'       => 'El stock mínimo debe ser mayor o igual a 1.',

        'cost_price.required' => 'El costo unitario es obligatorio.',
        'cost_price.numeric'  => 'El costo unitario debe ser un número.',
        'cost_price.decimal'  => 'El costo unitario puede tener como máximo 2 decimales.',
        'cost_price.min'      => 'El costo unitario no puede ser negativo.',

        'is_active.required'  => 'El estado del insumo es obligatorio.',
        'is_active.boolean'   => 'El estado del insumo no es válido.',

        'notes.string'        => 'Las notas deben ser un texto.',
        'notes.max'           => 'Las notas no pueden superar los 500 caracteres.',
    ];
}

}
