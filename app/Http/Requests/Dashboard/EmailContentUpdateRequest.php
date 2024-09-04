<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class EmailContentUpdateRequest extends FormRequest
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
        $countryId = implode(',', Country::pluck('id')->toArray());
        $generalStatus = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            'country_id' => "nullable | in:$countryId",
            'template' => 'nullable | string',
            'content_type' => 'nullable | string',
            'title' => 'nullable | string',
            'content' => 'nullable | string',
            'status' => "nullable | in:$generalStatus",
        ];
    }
}
