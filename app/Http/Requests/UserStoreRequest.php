<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use Illuminate\Foundation\Http\FormRequest;

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
            'confirm_password' => 'required_with:password|same:password|min:6',
        ];

    }

    public function messages()
    {
        return [
            'name.required' => 'Please provide your name.',
            'name.string' => 'Please enter your name using letters only in the name field.',
            'name.max' => 'Please keep your input within 24 letter.',
            'email.required' => 'Please provide your email address.',
            'password.required' => 'Please provide your password.',
            'password.max' => 'Please keep your input within 24 letter.',
            'password.min' => 'Please password field at least 6 letter.',
            'phone.regex' => 'Please provide your phone number will start only 9xxxxxxx.',
        ];
    }
}
