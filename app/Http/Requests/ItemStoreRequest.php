<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;

class ItemStoreRequest extends FormRequest
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
            'category_id' => "in:$categoryId | required",
            'name' => 'string',
            'image' => [
                'required',
                'array',
                'id' => ['required', 'numeric'],
                'is_feature' => ['required', 'boolean']
            ],
            'code' => ['unique:items,code', 'string'],
            'description' => 'string | nullable',
            'content' => 'string | nullable',
            'price' => 'numeric | nullable',
            'sell_price' => 'numeric',
            'out_of_stock' => 'boolean',
        ];
    }
}
