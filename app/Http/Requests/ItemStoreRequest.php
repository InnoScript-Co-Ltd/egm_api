<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Shop;
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
        $shopIds = implode(',', Shop::all()->pluck('id')->toArray());

        return [
            'category_id' => "in:$categoryId | required",
            'shop_id' => "required | in:$shopIds",
            'name' => 'string',
            'image' => ['required', 'array'],
            'image.*.id' => ['required'],
            'image.*.is_feature' => ['nullable', 'boolean'],
            'code' => ['unique:items,code', 'string'],
            'description' => 'string | nullable',
            'content' => 'string | nullable',
            'price' => 'required | numeric | nullable',
            'sell_price' => 'required | numeric',
            'out_of_stock' => 'boolean',
            'instock' => 'required | numeric',
        ];
    }
}
