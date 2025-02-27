<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminTradeRecordUpdateRequest extends FormRequest
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

        return [
            'title' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('trade_records', 'title'),
            ],
            'photos' => [
                'nullable',
                'array',
                'min:1',
            ],
            'photos.*' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif',
                'max:2048',
            ],
            'description' => [
                'nullable',
                'string',
                'min: 10',
            ],
            'content' => [
                'nullable',
                'string',
                'min: 10',
            ],

            'status' => [
                'nullable',
                'string',
                Rule::in(values: Enum::make(GeneralStatusEnum::class)->values()),
            ],
        ];
    }
}
