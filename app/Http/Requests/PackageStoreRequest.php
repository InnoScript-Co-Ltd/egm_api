<?php

namespace App\Http\Requests;

use App\Enums\PackageTypeEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class PackageStoreRequest extends FormRequest
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
        $packageTypes = implode(',', (new Enum(PackageTypeEnum::class))->values());

        return [
            'name' => 'required | string | unique:packages,name',
            'roi_rate' => 'required | numeric',
            'duration' => 'required | numeric',
            'deposit_amount' => 'required | array',
            'package_type' => "required | in:$packageTypes",
        ];
    }
}
