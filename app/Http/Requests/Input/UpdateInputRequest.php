<?php

namespace App\Http\Requests\Input;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInputRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir la actualización
    }

    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'unit'       => 'required|string|max:50',
            'stock'      => 'required|numeric|min:0',
            'min_stock'  => 'nullable|numeric|min:0',
            'cost_price' => 'required|numeric|decimal:0,2|min:0',
            'is_active'  => 'required|boolean',
            'notes'      => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'El nombre del insumo es obligatorio.',
            'name.string'         => 'El nombre debe ser un texto válido.',
            'name.max'            => 'El nombre no puede superar los 255 caracteres.',

            'unit.required'       => 'La unidad del insumo es obligatoria.',
            'unit.string'         => 'La unidad debe ser un texto válido.',
            'unit.max'            => 'La unidad no puede superar los 50 caracteres.',

            'stock.required'      => 'El stock es obligatorio.',
            'stock.numeric'       => 'El stock debe ser un número.',
            'stock.min'           => 'El stock debe ser mayor o igual a 0.',

            'min_stock.numeric'   => 'El stock mínimo debe ser un número.',
            'min_stock.min'       => 'El stock mínimo debe ser mayor o igual a 0.',

            'cost_price.required' => 'El precio de costo es obligatorio.',
            'cost_price.numeric'  => 'El precio de costo debe ser un número.',
            'cost_price.decimal'  => 'El precio de costo debe tener hasta 2 decimales.',
            'cost_price.min'      => 'El precio de costo no puede ser negativo.',

            'is_active.required'  => 'El estado es obligatorio.',
            'is_active.boolean'   => 'El estado debe ser activo o inactivo.',

            'notes.string'        => 'Las notas deben ser un texto válido.',
            'notes.max'           => 'Las notas no pueden superar los 500 caracteres.',
        ];
    }
}
