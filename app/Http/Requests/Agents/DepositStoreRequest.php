<?php

namespace App\Http\Requests\Agents;

use App\Models\AgentBankAccount;
use App\Models\MerchantBankAccount;
use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class DepositStoreRequest extends FormRequest
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
        $packageIds = implode(',', Package::pluck('id')->toArray());
        $agentBankAccountIds = implode(',', AgentBankAccount::pluck('id')->toArray());
        $merchantBankAccountIds = implode(',', MerchantBankAccount::pluck('id')->toArray());

        return [
            'package_id' => "required | in:$packageIds",
            'bank_account_id' => "required | in:$agentBankAccountIds",
            'merchant_account_id' => "required | in:$merchantBankAccountIds",
            'package_deposit_amount' => 'required | numeric',
            'bank_type' => 'required | string',
            'transaction_screenshoot' => 'required | file',
        ];
    }
}
