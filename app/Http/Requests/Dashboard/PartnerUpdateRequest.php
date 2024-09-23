<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Partner;
use Illuminate\Foundation\Http\FormRequest;

class PartnerUpdateRequest extends FormRequest
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
        $partner = Partner::findOrFail(request('id'));
        $partnerId = $partner->id;

        return [
            'first_name' => 'nullable | string',
            'last_name' => 'nullable | string',
            'username' => "nullable | unique:partners,username,$partnerId",
            'email' => "nullable | unique:partners,email,$partnerId",
            'phone' => "nullable | unique:partners,phone,$partnerId",
            'address' => 'nullable | string',
            'nrc' => "nullable | string | unique:partners,nrc,$partnerId",
            'dob' => 'nullable | date',
        ];
    }
}
