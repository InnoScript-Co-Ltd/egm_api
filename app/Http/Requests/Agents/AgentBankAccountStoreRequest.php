<?php

namespace App\Http\Requests\Agents;

use Illuminate\Foundation\Http\FormRequest;

class AgentBankAccountStoreRequest extends FormRequest
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
            'account_name' => 'required | string',
            'account_number' => 'required | unique:agent_bank_accounts,account_number',
            'branch_address' => 'required | string',
            'branch' => 'required | string',
            'bank_type' => 'required | string',
            'bank_type_label' => 'required | string',
        ];
    }
}
