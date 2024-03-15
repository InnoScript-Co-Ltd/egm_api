<?php

namespace App\Http\Requests;

use App\Models\MPECategory;
use App\Models\MPEUnit;
use Illuminate\Foundation\Http\FormRequest;

class MPEItemStoreRequest extends FormRequest
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
        $mpeCategoryId = implode(',', MPECategory::all()->pluck('id')->toArray());
        $mpeUnitId = implode(',', MPEUnit::all()->pluck('id')->toArray());

        return [
            'category_id' => "required|in:$mpeCategoryId",
            'unit_id' => "required|in:$mpeUnitId",
            'name' => 'required|string',
            'unit' => 'required|integer',
            'sell_price' => 'required | numeric',
            'discount_price' => 'required|numeric',
            'is_discount' => 'required|boolean',
            'is_promotion' => 'required|boolean',
        ];
    }
}
