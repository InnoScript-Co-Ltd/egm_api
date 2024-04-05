<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use Illuminate\Foundation\Http\FormRequest;

class UserClientRegisterRequest extends FormRequest
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
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;

        return [
            'name' => 'required | string',
            'email' => 'required | email | unique:users,email',
            'password' => 'required | confirmed',
            'client_type' => 'required | string',
        ];
    }
}
