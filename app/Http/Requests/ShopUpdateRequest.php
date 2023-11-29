<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Region;
use App\Enums\REGXEnum;
use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;

class ShopUpdateRequest extends FormRequest
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
        $regionId = implode(',', Region::all()->pluck('id')->toArray());
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            "region_id" => "required | in:$regionId",
            "name" => "string",
            'phone' => ['nullable', 'string', "regex:$mobileRule"],
            "address" => "string",
            "location" => "string",
            "status" => "in:$generalStatusEnum | nullable | string"
        ];
    }
}
