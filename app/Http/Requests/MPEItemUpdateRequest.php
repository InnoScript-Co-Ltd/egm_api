<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\MPECategory;
use App\Models\MPEUnit;
use Illuminate\Foundation\Http\FormRequest;

class MPEItemUpdateRequest extends FormRequest
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
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            'category_id' => "nullable|in:$mpeCategoryId",
            'unit_id' => "nullable|in:$mpeUnitId",
            'name' => 'nullable|string',
            'unit' => 'nullable|integer',
            'sell_price' => 'nullable | numeric',
            'discount_price' => 'nullable|numeric',
            'is_discount' => 'nullable|boolean',
            'is_promotion' => 'nullable|boolean',
            'status' => "nullable|in:$generalStatusEnum",
        ];
    }
}
