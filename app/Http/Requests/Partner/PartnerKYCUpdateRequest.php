<?php

namespace App\Http\Requests\Partner;

use Illuminate\Foundation\Http\FormRequest;

class PartnerKYCUpdateRequest extends FormRequest
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
            'nrc_front' => 'nullable | file | max:1024',
            'nrc_back' => 'nullable | file | max:1024',
            'nrc' => "nullable | string | unique:partners,nrc,$partnerId",
            'dob' => 'nullable | date',
        ];
    }
}
