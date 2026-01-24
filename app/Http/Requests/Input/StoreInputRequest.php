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
            'name'       => 'required|string|max:255',
            'unit'       => 'required|string|max:50',
            'stock'      => 'required|numeric|min:0',
            'min_stock'  => 'nullable|numeric|min:0',
            'is_active'  => 'required|boolean',
            'notes'      => 'nullable|string|max:500',
        ];
    }
}
