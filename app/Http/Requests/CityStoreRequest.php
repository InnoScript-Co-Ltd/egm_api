<?php

namespace App\Http\Requests;

use App\Models\RegionOrState;
use Illuminate\Foundation\Http\FormRequest;

class CityStoreRequest extends FormRequest
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

        return [
            'name' => 'required | string | unique:cities,name',
            'region_or_state_id' => "required | in:$regionOrStates",
        ];
    }
}
