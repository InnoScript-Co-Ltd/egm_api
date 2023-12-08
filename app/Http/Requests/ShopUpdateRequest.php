<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Enums\REGXEnum;
use App\Helpers\Enum;
use App\Models\Region;
use Illuminate\Foundation\Http\FormRequest;

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
            'region_id' => "nullable | in:$regionId",
            'name' => 'string',
            'phone' => ['nullable', 'string', "regex:$mobileRule"],
            'address' => 'string',
            'location' => 'string',
            'status' => "in:$generalStatusEnum | nullable | string",
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Please enter your name using letters only in the name field.',
            'phone.regex' => 'Please provide your phone number will start only 9xxxxxxx.',
            'address.string' => 'Please enter your address using letters only in the address field',
            'location.string' => 'Please enter your location using letters only in the address field',
            'status.in' => 'Please choose shop status'
        ];
    }
}
