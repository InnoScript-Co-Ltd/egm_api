<?php

namespace App\Http\Requests\Partner;

use App\Models\PartnerBankAccount;
use Illuminate\Foundation\Http\FormRequest;

class PartnerBankAccountUpdateRequest extends FormRequest
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
        $bankAccount = PartnerBankAccount::findOrFail(request('id'));
        $bankAccountId = $bankAccount['id'];
        $status = implode(',', ['ACTIVE', 'DISABLE']);

        return [
            'account_name' => 'nullable | string',
            'account_number' => "nullable | unique:partner_bank_accounts,account_number,$bankAccountId",
            'branch_address' => 'nullable | string',
            'branch' => 'nullable | string',
            'bank_type' => 'nullable | string',
            'bank_type_label' => 'nullable | string',
            'status' => "nullable | string | in:$status",
        ];
    }
}
