<?php

namespace App\Http\Requests\Partner;

use App\Models\Partner;
use Illuminate\Foundation\Http\FormRequest;

class GenerateReferenceLinkRequest extends FormRequest
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
        $partnerIds = implode(',', Partner::pluck('id')->toArray());

        return [
            'partner_id' => "required | in:$partnerIds",
        ];
    }

    public function messages(): array
    {
        return [
            'partner_id.in' => 'Partner is not found',
        ];
    }
}
