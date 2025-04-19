<?php

namespace App\Http\Requests\Partner;

use App\Enums\GeneralStatusEnum;
use App\Enums\PartnerStatusEnum;
use App\Helpers\Enum;
use App\Models\Partner;
use Illuminate\Foundation\Http\FormRequest;

class PartnerUSDTAddressStoreRequest extends FormRequest
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

        return [
            'partner_id' => "required | in:$partnerIds",
            'email' => 'required | email | unique:usdt_address,email',
            'phone' => 'required | string',
            'name' => 'required | string',
            'address' => 'required | string | unique:usdt_address,address',
            'address_type' => 'required | string',
        ];
    }
}
