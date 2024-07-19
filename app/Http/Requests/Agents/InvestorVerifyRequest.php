<?php

namespace App\Http\Requests\Agents;

use App\Models\Investor;
use Illuminate\Foundation\Http\FormRequest;

class InvestorVerifyRequest extends FormRequest
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
        $investorIds = implode(',', Investor::pluck('id')->toArray());

        return [
            'investor_id' => "required | in:$investorIds",
            'email_verify_code' => 'required | string | min:6 | max:6',
        ];
    }
}
