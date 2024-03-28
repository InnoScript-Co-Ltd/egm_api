<?php

namespace App\Http\Requests;

use App\Enums\AppTypeEnum;
use App\Enums\REGXEnum;
use App\Helpers\Enum;
use App\Models\City;
use App\Models\Country;
use App\Models\RegionOrState;
use App\Models\Township;
use Illuminate\Foundation\Http\FormRequest;

class ShopStoreRequest extends FormRequest
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
        $countriesId = implode(',', Country::pluck('id')->toArray());
        $regionOrStatesId = implode(',', RegionOrState::pluck('id')->toArray());
        $citiesId = implode(',', City::pluck('id')->toArray());
        $townshopsId = implode(',', Township::pluck('id')->toArray());

        $appType = implode(',', (new Enum(AppTypeEnum::class))->values());
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;

        return [
            'country_id' => "required | in:$countriesId",
            'region_or_state_id' => "required | in:$regionOrStatesId",
            'city_id' => "required | in:$citiesId",
            'township_id' => "required | in:$townshopsId",
            'cover_photo' => 'required | image:mimes:jpeg,png,jpg,gif|max:2048',
            'shop_logo' => 'required |  image:mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required | string',
            'phone' => ['required', 'string', "regex:$mobileRule"],
            'email' => 'required | string | unique:shops,email',
            'address' => 'required | string',
            'app_type' => "required | string | in:$appType",
            'description' => 'nullable | string',
        ];
    }
}
