<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\REGXEnum;

class UserStoreRequest extends FormRequest
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
            'name' => 'required | string | max: 24 | min: 8',
            'email' => 'required | email | unique:users,email',
            'phone' => ['required', 'unique:users,phone', "regex:$mobileRule"],
            'password' => 'required | max: 24 | min: 6',
            "confirm_password" => 'required_with:password|same:password|min:6'
        ];

    }
}
