<?php

namespace App\Http\Requests;

use App\Models\Item;
use Illuminate\Foundation\Http\FormRequest;

class PromotionItemStoreRequest extends FormRequest
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
        $items = implode(',', Item::where(['status' => 'ACTIVE'])->pluck('id')->toArray());

        return [
            'promotion_price' => 'nullable | numeric',
            'item_ids' => 'required | array',
            'item_ids.*' => "in:$items|unique:items_in_promotion,id",
        ];
    }
}
