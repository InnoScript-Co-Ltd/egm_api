<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;

class TownshipUpdateRequest extends FormRequest
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
        $citiesId = implode(',', City::pluck('id')->toArray());

        return [
            'name' => 'nullable | string',
            'city_id' => "nullable | in:$citiesId",
            'status' => "nullable | in:$generalStatusEnum",
        ];
    }
}
