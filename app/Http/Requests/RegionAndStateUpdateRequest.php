<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\RegionOrState;
use Illuminate\Foundation\Http\FormRequest;

class RegionAndStateUpdateRequest extends FormRequest
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
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());
        $regionOrStateIds = implode(',', RegionOrState::pluck('id')->toArray());
        $regionOrState = RegionOrState::findOrFail(request('id'));
        $regionOrStateId = $regionOrState->id;

        return [
            'name' => "nullable | string | unique:regions_or_states,name,$regionOrStateId",
            'country_id' => "nullable | in:$regionOrStateIds",
            'status' => "nullable | in:$generalStatusEnum",
        ];
    }
}
