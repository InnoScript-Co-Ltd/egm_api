<?php

namespace App\Http\Requests;

use App\Enums\AppTypeEnum;
use App\Enums\GeneralStatusEnum;
use App\Enums\REGXEnum;
use App\Helpers\Enum;
use App\Models\City;
use App\Models\Country;
use App\Models\RegionOrState;
use App\Models\Shop;
use App\Models\Township;
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
        $countriesId = implode(',', Country::pluck('id')->toArray());
        $regionOrStatesId = implode(',', RegionOrState::pluck('id')->toArray());
        $citiesId = implode(',', City::pluck('id')->toArray());
        $townshopsId = implode(',', Township::pluck('id')->toArray());

        $appType = implode(',', (new Enum(AppTypeEnum::class))->values());
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        $shop = Shop::findOrFail(request('id'));
        $shopId = $shop->id;

        return [
            'country_id' => "nullable | in:$countriesId",
            'region_or_state_id' => "nullable | in:$regionOrStatesId",
            'city_id' => "nullable | in:$citiesId",
            'township_id' => "nullable | in:$townshopsId",
            'cover_photo' => 'nullable',
            'shop_logo' => 'nullable ',
            'name' => 'nullable | string',
            'phone' => ['nullable', 'string', "regex:$mobileRule"],
            'email' => "nullable | string | unique:shops,email,$shopId",
            'address' => 'nullable | string',
            'app_type' => "nullable | string | in:$appType",
            'description' => 'nullable | string',
            'location' => 'nullable | string',
            'status' => "nullable | string | in:$generalStatusEnum",
        ];
    }
}
