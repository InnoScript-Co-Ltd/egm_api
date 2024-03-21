<?php

namespace App\Http\Requests;

use App\Enums\AppTypeEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
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
        $appTypes = implode(',', (new Enum(AppTypeEnum::class))->values());

        return [
            'name' => 'required | string | unique:categories,name',
            'icon' => 'nullable | image:mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable | string',
            'app_type' => "required | string | in:$appTypes",
        ];
    }
}
