<?php

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class PartnerCreateRequest extends FormRequest
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
            'first_name' => 'required | string',
            'last_name' => 'required | string',
            'email' => 'required | email | unique:partners,email',
            'phone' => 'required | unique:partners,phone',
            'referral' => 'nullable | string',
            'password' => 'required | confirmed | min:6 | max:18',
        ];
    }
}
