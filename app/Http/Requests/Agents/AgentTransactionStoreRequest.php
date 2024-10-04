<?php

namespace App\Http\Requests\Agents;

// use App\Enums\PackageTypeEnum;
// use App\Models\Package;
use App\Models\AgentBankAccount;
use App\Models\MerchantBankAccount;
use Illuminate\Foundation\Http\FormRequest;

class AgentTransactionStoreRequest extends FormRequest
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
        // $packageIds = implode(',', Package::where(['package_type' => PackageTypeEnum::AGENT])->pluck('id')->toArray());
        $agentBankAccountIds = implode(',', AgentBankAccount::pluck('id')->toArray());
        $merchantBankAccountIds = implode(',', MerchantBankAccount::where(['status' => 'ACTIVE'])->pluck('id')->toArray());

        return [
            // 'package_id' => "required | in:$packageIds",
            'bank_account_id' => "required | in:$agentBankAccountIds",
            'merchant_account_id' => "required | in:$merchantBankAccountIds",
            'package_deposit_amount' => 'required | numeric',
            'transaction_screenshoot' => 'required | file | max:1024',
        ];
    }
}
