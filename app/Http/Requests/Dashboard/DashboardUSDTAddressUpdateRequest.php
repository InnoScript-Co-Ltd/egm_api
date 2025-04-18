<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\GeneralStatusEnum;
use App\Enums\PartnerStatusEnum;
use App\Helpers\Enum;
use App\Models\Partner;
use App\Models\USDTAddress;
use Illuminate\Foundation\Http\FormRequest;

class DashboardUSDTAddressUpdateRequest extends FormRequest
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
        $partnerIds = implode(',', Partner::where(['status' => PartnerStatusEnum::ACTIVE->value])->pluck('id')->toArray());
        $generalStatus = implode(',', (new Enum(GeneralStatusEnum::class))->values());
        $usdtAddress = USDTAddress::findOrFail(request('id'));
        $usdtAddressId = $usdtAddress->id;

        return [
            'partner_id' => "nullable | in:$partnerIds",
            'email' => 'nullable | email',
            'phone' => 'nullable | string',
            'name' => 'nullable | string',
            'address' => "nullable | string | unique:usdt_address,address,$usdtAddressId",
            'address_type' => 'nullable | string',
            'status' => "nullable | string | in:$generalStatus",
        ];
    }
}
