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
            'category_id' => "required | in:$categoryId",
            'shop_id' => "required | in:$shopIds",
            'name' => 'required | string',
            'thumbnail_photo' => 'nullable | file',
            'product_photo' => 'nullable | array',
            'product_photo.*' => 'required|file',
            'item_code' => ['required', 'unique:items,item_code', 'string'],
            'item_color' => 'required|array',
            'item_color.*' => 'required|string',
            'item_size' => 'required|array',
            'item_size.*' => 'required|string',
            'description' => 'string | nullable',
            'content' => 'string | nullable',
            'price' => 'nullable | numeric',
            'sell_price' => 'required | numeric',
            'instock' => 'required | numeric',
        ];
    }
}
