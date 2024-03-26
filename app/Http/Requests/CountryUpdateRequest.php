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
        $countryId = $country->id();

        $generalStatus = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            'name' => "nullable | string | unique:name,$countryId",
            'status' => "nullable | string | in:$generalStatus",
        ];
    }
}
