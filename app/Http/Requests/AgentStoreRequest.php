<?php

namespace App\Http\Requests;

use App\Models\City;
use App\Models\Country;
use App\Models\RegionOrState;
use App\Models\Township;
use App\Enums\REGXEnum;
use Illuminate\Foundation\Http\FormRequest;

class AgentStoreRequest extends FormRequest
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
        $regionOrStateId = implode(',', RegionOrState::pluck('id')->toArray());
        $citiesId = implode(',', City::pluck('id')->toArray());
        $townshipsId = implode(',', Township::pluck('id')->toArray());

        $mobileRule = REGXEnum::MOBILE_NUMBER->value;

        return [
            "profile" => "nullable | file",
            "first_name" => "required | string | min:3 | max:18",
            "last_name" => "required | string | min:3 | max:18",
            "phone" => ["required", "string", "unique:agents,phone", "regex:$mobileRule"],
            "prefix" => "required | string",
            "email" => "required | string | unique:agents,email",
            "dob" => "required | date",
            "nrc" => "nullable | string | unique:agents,nrc",
            // "passport" => "nullable | string | unique:agents,passport",
            "nrc_front" => "nullable | file",
            "nrc_back" => "nullable | file",
            // "passport_front" => "nullable | file",
            // "passport_back" => "nullable | file",
            "country_id" => "required | in:$countriesId",
            "region_or_state_id" => "required | in:$regionOrStateId",
            "city_id" => "required | in:$citiesId",
            "township_id" => "required | in:$townshipsId",
            "address" => "required | string"
        ];
    }
}
