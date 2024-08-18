<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\MerchantBankAccount;
use Illuminate\Foundation\Http\FormRequest;

class MerchantBankAccountUpdateRequest extends FormRequest
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
        $merchantBankAccount = MerchantBankAccount::findOrFail(request('id'));
        $merchantBankAccountId = $merchantBankAccount->id;
        $generalStatus = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            'bank_type' => 'nullable | string',
            'bank_type_label' => 'nullable | string',
            'holder_name' => 'nullable | string',
            'account_number' => "nullable | string | unique:merchant_bank_accounts,account_number,$merchantBankAccountId",
            'status' => "nullable | in:$generalStatus",
        ];
    }
}
