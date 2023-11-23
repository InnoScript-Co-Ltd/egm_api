<?php

namespace App\Http\Requests;

use App\Enums\PointLabelEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class PointStoreRequest extends FormRequest
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
        $pointLabelEnum = implode(',', (new Enum(PointLabelEnum::class))->values());

        return [
            'label' => ['required', 'string', 'unique:points,label', "in:$pointLabelEnum"],
            'point' => 'required | numeric',
        ];
    }
}
