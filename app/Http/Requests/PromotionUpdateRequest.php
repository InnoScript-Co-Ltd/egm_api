<?php

namespace App\Http\Requests;

use App\Enums\AppTypeEnum;
use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class PromotionUpdateRequest extends FormRequest
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
        $appTypes = implode(',', (new Enum(AppTypeEnum::class))->values());

        return [
            'title' => 'nullable | string',
            'image' => 'nullable | image:mimes:jpeg,png,jpg,gif|max:2048',
            'app_type' => "nullable | string | in:$appTypes",
            'start_date' => 'nullable | date',
            'end_date' => 'nullable | date',
            'status' => "nullable | string | in:$generalStatusEnum",
        ];
    }
}
