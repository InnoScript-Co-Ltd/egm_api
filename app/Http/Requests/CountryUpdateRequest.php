<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class CountryUpdateRequest extends FormRequest
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
        $country = Country::findOrFail(request('id'));
        $countryId = $country->id;

        $generalStatus = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            'name' => "nullable | string | unique:countries,name,$countryId",
            'country_code' => "nullable | string | min:2 | max:4 | unique:countries,country_code,$countryId",
            'mobile_prefix' => "nullable | string | unique:countries,mobile_prefix,$countryId",
            'flag_image' => 'nullable |  image:mimes:jpeg,png,jpg,gif|max:2048',
            'status' => "nullable | string | in:$generalStatus",
        ];
    }
}
