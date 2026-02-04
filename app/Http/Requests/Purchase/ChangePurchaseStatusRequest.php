<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class ChangePurchaseStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,approved,in_transit,completed,cancelled'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
        ];
    }
}
