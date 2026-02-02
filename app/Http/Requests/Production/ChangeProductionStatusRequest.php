<?php

namespace App\Http\Requests\Production;

use Illuminate\Foundation\Http\FormRequest;

class ChangeProductionStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // luego podés meter policies
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                'in:draft,confirmed,cancelled',
            ],
        ];
    }
}
