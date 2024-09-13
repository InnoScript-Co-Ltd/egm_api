<?php

namespace App\Http\Requests\Partner;

use App\Enums\GeneralStatusEnum;
use App\Enums\PackageTypeEnum;
use App\Models\MerchantBankAccount;
use App\Models\Package;
use App\Models\PartnerBankAccount;
use Illuminate\Foundation\Http\FormRequest;

class PartnerTransactionStoreRequest extends FormRequest
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
        $merchantAccounts = implode(',', MerchantBankAccount::where(['status' => 'ACTIVE'])->pluck('id')->toArray());

        $depositPackages = implode(',', Package::where([
            'status' => GeneralStatusEnum::ACTIVE->value,
            'package_type' => PackageTypeEnum::PARTNER->value,
        ])->pluck('id')->toArray());

        $partnerBankAccounts = implode(',', PartnerBankAccount::where([
            'status' => GeneralStatusEnum::ACTIVE->value,
        ])->pluck('id')->toArray());

        return [
            'merchant_account_id' => "required | in:$merchantAccounts",
            'package_id' => "required | in:$depositPackages",
            'transaction_screenshoot' => 'required | file | max:1024',
            'package_deposit_amount' => 'required | numeric',
            'sender_account_id' => "required | in:$partnerBankAccounts",
        ];
    }
}
