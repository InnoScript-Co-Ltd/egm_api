<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Enums\PackageTypeEnum;
use App\Helpers\Enum;
use App\Models\Package;
use Illuminate\Foundation\Http\FormRequest;

class PackageUpdateRequest extends FormRequest
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
        $package = Package::findOrFail(request('id'));
        $packageId = $package->id;

        $generalStatusIds = implode(',', (new Enum(GeneralStatusEnum::class))->values());
        $packageType = implode(',', (new Enum(PackageTypeEnum::class))->values());

        return [
            'name' => "nullable | string | unique:packages,name,$packageId",
            'roi_rate' => 'nullable | string',
            'duration' => 'nullable | numeric',
            'deposit_amount' => 'nullable | array',
            'status' => "nullable | string | in:$generalStatusIds",
            'package_type' => "nullable | string | in:$packageType",
        ];
    }
}
