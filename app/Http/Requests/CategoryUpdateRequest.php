<?php

namespace App\Http\Requests;

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

        $categoryIds = implode(',', Category::all()->pluck('id')->toArray());
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        $category = Category::FindOrFail(request('id'));
        $categoryId = $category->id;

        return [
            'title' => "nullable | string | unique:categories,title,$categoryId",
            'level' => 'nullable | numeric',
            'icon' => 'nullable | numeric',
            'category_id' => "nullable | in:$categoryIds",
            'description' => 'nullable | string',
            'status' => "nullable | in:$generalStatusEnum",
        ];
    }
}
