<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Por ahora permitimos siempre, luego se puede conectar con policies
        return true;
    }

    public function rules(): array
    {
        return [
            
            
            // Cliente
            'customer_id' => ['nullable', 'exists:customers,id'], // Cliente existente (opcional)
            'customer_name' => ['nullable', 'string', 'max:255'], // Nombre del cliente

            // Pedido
              'status'        => 'required|string',
            
              // Estado del pedido

            'order_type' => [
                'required',
                'in:delivery,in_store'
            ], // Tipo de pedido

            'address' => [
                'nullable',
                'string',
                'max:255'
            ], // Dirección (obligatoria si es delivery)

            'payment_method' => [
                'required',
                'in:cash,card,qr,other'
            ], // Método de pago

            'notes' => ['nullable', 'string'],

            // Productos
            'products' => ['required', 'array', 'min:1'], // Debe haber al menos un producto

            'products.*.product_id' => [
                'required',
                'exists:products,id'
            ], // Producto válido

            'products.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ], // Cantidad mínima 1

            'products.*.unit_price' => [
                'required',
                'numeric',
                'min:0'
            ], // Precio unitario válido
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Si es delivery, la dirección es obligatoria
            if ($this->order_type === 'delivery' && empty($this->address)) {
                $validator->errors()->add(
                    'address',
                    'La dirección es obligatoria para pedidos a domicilio.'
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'El nombre del cliente es obligatorio.',
            'products.required' => 'Debe agregar al menos un producto al pedido.',
            'products.min' => 'El pedido debe tener al menos un producto.',
            'products.*.product_id.required' => 'Debe seleccionar un producto.',
            'products.*.quantity.min' => 'La cantidad debe ser mayor a 0.',
        ];
    }
}
