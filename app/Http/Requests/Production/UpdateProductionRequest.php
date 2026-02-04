<?php

namespace App\Http\Requests\Production;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Production;

class UpdateProductionRequest extends FormRequest
{
    public function authorize(): bool
    {
       $productionId = $this->route('production');

    $production = Production::find($productionId);

    return $production && $production->status !== 'cancelled';
    }

    public function rules(): array
    {
        return [
            // Fecha
            'date' => ['required', 'date'],

            // Nota de corrección
            'notes' => ['nullable', 'string', 'max:255'],

            // Insumos
            'inputs' => ['required', 'array', 'min:1'],
            'inputs.*.inputs_id' => ['required', 'exists:inputs,id'],
            'inputs.*.quantity'  => ['required', 'numeric', 'min:0.001'],

            // Productos
            'products' => ['required', 'array', 'min:1'],
            'products.*.product_id' => ['required', 'exists:products,id'],
            'products.*.quantity'   => ['required', 'numeric', 'min:0.001'],
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'La fecha de producción es obligatoria',

            'inputs.required' => 'Debe registrar al menos un insumo consumido',
            'inputs.min' => 'Debe registrar al menos un insumo',

            'inputs.*.inputs_id.required' => 'Debe seleccionar un insumo',
            'inputs.*.inputs_id.exists' => 'El insumo seleccionado no existe',
            'inputs.*.quantity.required' => 'Debe indicar la cantidad del insumo',
            'inputs.*.quantity.min' => 'La cantidad del insumo debe ser mayor a 0',

            'products.required' => 'Debe registrar al menos un producto generado',
            'products.min' => 'Debe registrar al menos un producto',

            'products.*.product_id.required' => 'Debe seleccionar un producto',
            'products.*.product_id.exists' => 'El producto seleccionado no existe',
            'products.*.quantity.required' => 'Debe indicar la cantidad producida',
            'products.*.quantity.min' => 'La cantidad producida debe ser mayor a 0',
        ];
    }

    protected function failedAuthorization()
    {
        abort(403, 'No se puede editar una producción cancelada');
    }
}
