<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\City;
use App\Models\RegionOrState;
use Illuminate\Foundation\Http\FormRequest;

class CityUpdateRequest extends FormRequest
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
        $regionOrStates = implode(',', RegionOrState::pluck('id')->toArray());
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        $city = City::findOrFail(request('id'));
        $cityId = $city->id;

        return [
            'name' => "nullable | string | unique:cities,name,$cityId",
            'region_or_state_id' => "nullable | in:$regionOrStates",
            'status' => "nullable | string | in:$generalStatusEnum",
        ];
    }
}
