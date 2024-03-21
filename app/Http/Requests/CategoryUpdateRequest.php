<?php

namespace App\Http\Requests;

use App\Enums\AppTypeEnum;
use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
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

        $categoryId = Category::findOrFail(request('id'))->id;
        $generalStatus = implode(',', (new Enum(GeneralStatusEnum::class))->values());
        $appTypes = implode(',', (new Enum(AppTypeEnum::class))->values());

        return [
            'name' => "nullable | string | unique:categories,name,$categoryId",
            'icon' => 'nullable | image:mimes:jpeg,png,jpg,gif|max:2048',
            'app_type' => "nullable | string | in:$appTypes",
            'description' => 'nullable | string',
            'status' => "nullable | string | in:$generalStatus",
        ];
    }
}
