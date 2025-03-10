<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\KycStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class PartnerApproveRequest extends FormRequest
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
        $approveStatus=implode(',',(new Enum(KycStatusEnum::class))->values());

        return [
            'kyc_status' => "required | string | in:$approveStatus",
        ];
    }
}
