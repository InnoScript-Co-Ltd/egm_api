<?php

namespace App\Http\Requests\Partner;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class PartnerUSDTAddressUpdateRequest extends FormRequest
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
        $generalStatus = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            'status' => "nullable | string | in:$generalStatus",
        ];
    }
}
