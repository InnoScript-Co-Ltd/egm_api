<?php

namespace App\Http\Requests\Agents;

use Illuminate\Foundation\Http\FormRequest;

class AccountUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile' => 'nullable | file',
            'first_name' => 'nullable | string | min:2 | max:18',
            'last_name' => 'nullable | string | min:2 | max:18',
            'address' => 'nullable | string',
        ];
    }
}
