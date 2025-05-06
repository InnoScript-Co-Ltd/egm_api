<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MerchantBankAccountStoreRequest extends FormRequest
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
            'bank_type_label' => 'required | string',
            'holder_name' => 'required | string',
            'account_number' => 'required | string | unique:merchant_bank_accounts,account_number',
            'transaction_limit' => 'required | numeric',
            'bank_type' => 'required | string',
        ];
    }
}
