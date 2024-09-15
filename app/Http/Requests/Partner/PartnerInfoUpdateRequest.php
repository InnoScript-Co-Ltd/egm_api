<?php

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class PartnerInfoUpdateRequest extends FormRequest
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

        $partner = auth('partner')->user();
        $partnerId = $partner->id;

        return [
            'username' => "nullable | string | unique:partners,username,$partnerId",
            'first_name' => 'nullable | string | min:2 | max:18',
            'last_name' => 'nullable | string | min:2 | max:18',
            'address' => 'nullable | string',
        ];
    }
}
