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
            'occupation' => 'nullable | string',
            'position' => 'nullable | string',
            'address' => 'nullable | string',
            'dob' => 'nullable | date',
        ];

    }

    public function messages()
    {
        return [
            'name.required' => 'Please provide your name.',
            'name.string' => 'Please enter your name using letters only in the name field.',
            'name.max' => 'Please keep your input within 24 letter.',
            'email.required' => 'Please provide your email address.',
            'phone.regex' => 'Please provide your phone number will start only 9xxxxxxx.',
            'dpb.date' => 'invalid date format',
        ];
    }
}
