<?php

namespace App\Http\Requests\Agents;

use App\Models\AgentBankAccount;
use Illuminate\Foundation\Http\FormRequest;

class AgentBankAccountUpdateRequest extends FormRequest
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
        $bankAccount = AgentBankAccount::findOrFail(request('id'));
        $bankAccountId = $bankAccount->id;

        return [
            'account_name' => 'nullable | string',
            'account_number' => "nullable | unique:agent_bank_accounts,account_number,$bankAccountId",
            'branch_address' => 'nullable | string',
            'branch' => 'nullable | string',
            'bank_type' => 'nullable | string',
            'bank_type_label' => 'nullable | string',
        ];
    }
}
