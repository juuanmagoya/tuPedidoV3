<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Cliente
            'customer_id' => ['nullable', 'exists:customers,id'],
            'customer_name' => ['nullable', 'string', 'max:255'],

            // Pedido
            // El status NO se actualiza desde el form
            // Se controla desde el Service
            'order_type' => [
                'required',
                'in:delivery,in_store'
            ],

            'address' => [
                'nullable',
                'string',
                'max:255'
            ],

            'payment_method' => [
                'required',
                'in:cash,card,qr,other'
            ],

            'notes' => ['nullable', 'string'],

            // Productos (obligatorios porque se rehace el pedido)
            'products' => ['required', 'array', 'min:1'],

            'products.*.product_id' => [
                'required',
                'exists:products,id'
            ],

            'products.*.quantity' => [
                'required',
                'integer',
                'min:1'
            ],

            'products.*.unit_price' => [
                'required',
                'numeric',
                'min:0'
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->order_type === 'delivery' && empty($this->address)) {
                $validator->errors()->add(
                    'address',
                    'La dirección es obligatoria para pedidos a domicilio.'
                );
            }
        });
    }
}
