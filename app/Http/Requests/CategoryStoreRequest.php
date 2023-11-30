<?php

namespace App\Http\Requests;

use App\Models\Category;
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

        $categoryId = implode(',', Category::all()->pluck('id')->toArray());

        return [
            'title' => 'string | required | unique:categories,title',
            'level' => 'numeric | nullable',
            'icon' => 'numeric',
            'category_id' => "nullable | in:$categoryId",
            'description' => 'string | nullable',
        ];
    }
}
