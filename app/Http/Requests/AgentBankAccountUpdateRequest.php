<?php

namespace App\Http\Requests;

use App\Enums\BankAccountStatusEnum;
use App\Enums\DefaultStatusEnum;
use App\Helpers\Enum;
use App\Models\Agent;
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
        $agentIds = implode(',', Agent::pluck('id')->toArray());
        $defaultAccountStatus = implode(',', (new Enum(DefaultStatusEnum::class))->values());
        $bankAccountStatus = implode(',', (new Enum(BankAccountStatusEnum::class))->values());

        return [
            'agent_id' => "nullable | in:$agentIds",
            'account_name' => 'nullable | string',
            'account_number' => 'nullable | uniques,agent_bank_accounts,account_number',
            'address' => 'nullable | string',
            'branch' => 'nullable | string',
            'default_account' => "nullable | string | in:$defaultAccountStatus",
            'status' => "nullable | string | in:$bankAccountStatus",
        ];
    }
}
