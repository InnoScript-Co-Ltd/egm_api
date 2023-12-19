<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Foundation\Http\FormRequest;

class ItemUpdateRequest extends FormRequest
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
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());
        $shopIds = implode(',', Shop::all()->pluck('id')->toArray());

        return [
            'category_id' => "nullable | in:$categoryId",
            'shop_id' => "nullable | in:$shopIds",
            'name' => 'string',
            'code' => 'string',
            'description' => 'string | nullable',
            'content' => 'string | nullable',
            'price' => 'numeric | nullable',
            'sell_price' => 'numeric',
            'out_of_stock' => 'boolean',
            'status' => "nullable | in:$generalStatusEnum",
            'instock' => 'nullable | numeric',
            'image' => 'nullable | array',
            'image.*.id' => ['required'],
            'image.*.is_feature' => ['nullable', 'boolean'],
        ];
    }
}
