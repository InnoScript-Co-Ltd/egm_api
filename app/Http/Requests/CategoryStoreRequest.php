<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

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
            "title" => "string | required",
            "level" => "numeric | nullable",
            "category_id" => "in:$categoryId | nullable",
            "description" => "string | nullable"
        ];
    }
}
