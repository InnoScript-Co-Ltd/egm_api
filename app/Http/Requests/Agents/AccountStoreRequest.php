<?php

namespace App\Http\Requests\Agents;

use App\Enums\REGXEnum;
use Illuminate\Foundation\Http\FormRequest;

class AccountStoreRequest extends FormRequest
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
            'first_name' => 'required | string | min:2 | max:18',
            'last_name' => 'required | string | min:2 | max:18',
            'email' => 'required | email | unique:agents,email',
            'phone' => ['required', 'unique:agents,phone', "regex:$mobileRule"],
            'password' => 'required | string | min:6 | max:18 | confirmed',
        ];
    }
}
