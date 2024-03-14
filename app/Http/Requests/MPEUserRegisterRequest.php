<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MPEUserRegisterRequest extends FormRequest
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
            'email' => 'required | email | unique:mpe_users,email',
            'password' => 'required | string | confirmed',
            'name' => 'required | string',
            'gender' => 'required | string',
        ];
    }
}
