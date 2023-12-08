<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class RegionUpdateRequest extends FormRequest
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

        return [
            'name' => 'string',
            'status' => "nullable | in:$generalStatusEnum",
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Please enter your name using letters only in the name field.',
        ];
    }
}
