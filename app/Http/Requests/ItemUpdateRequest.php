<?php

namespace App\Http\Requests;

use App\Models\Item;
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
        $item = Item::find(request('id'));
        $itemIds = $item->id;

        return [
            'name' => 'nullable | string',
            'thumbnail_photo' => 'nullable',
            'product_photo' => 'nullable | array',
            'product_photo.*' => 'nullable|file',
            'item_code' => "nullable | unique:items,item_code,$itemIds | string",
            'item_color' => 'nullable|array',
            'item_color.*' => 'nullable|string',
            'item_size' => 'nullable|string',
            'description' => 'string | nullable',
            'content' => 'string | nullable',
            'price' => 'nullable | numeric',
            'sell_price' => 'nullable | numeric',
            'instock' => 'nullable | numeric',
            'status' => "nullable | in:$generalStatusEnum",
        ];
    }
}
