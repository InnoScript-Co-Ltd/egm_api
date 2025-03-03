<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\GeneralStatusEnum;
use App\Models\BankType;
use Illuminate\Foundation\Http\FormRequest;

class AdminBankTypeUpdateRequest extends FormRequest
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
        $bankType = BankType::findOrFail(request('id'));
        $bankTypeId = $bankType->id;
        $generalStatus = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            'logo' => 'nullable | file',
            'bank_type' => "nullable | string | unique:bank_types,bank_type,$bankTypeId",
            'bank_name' => "nullable | string | unique:bank_types,bank_name,$bankTypeId",
            'status' => 'nullable | string',
        ];
    }
}
