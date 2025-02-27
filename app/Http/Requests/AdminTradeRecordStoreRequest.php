<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminTradeRecordStoreRequest extends FormRequest
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
         {
            return [
                'title' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('trade_records', 'title'),
                ],
                'photos' => [
                    'required',
                    'array',
                    'min:1',
                    'max:5',
                ],
                'photos.*' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,gif',
                    'max:2048',
                ],
                'description' => [
                    'required',
                    'string',
                    'min: 10',
                ],
                'content' => [
                    'required',
                    'string',
                    'min: 10',
                ],

                'status' => [
                    'required',
                    'string',
                    Rule::in(values: Enum::make(GeneralStatusEnum::class)->values()),
                ],
            ];
        }
    }
}
