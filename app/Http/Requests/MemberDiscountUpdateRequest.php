<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\MemberDisocunt;
use App\Enums\MemberDiscountStatusEnum;

class MemberDiscountUpdateRequest extends FormRequest
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
        $memberDiscount = MemberDisocunt::findOrFail(request('id'));
        $memberDiscountId = $memberDiscount->id;

        $memberDiscountStatusEnum = implode(',', (new Enum(MemberDiscountStatusEnum::class))->values());

        return [
            "label" => "required | string | unique:member_discounts,label,$memberDiscountId",
            "discount_percentage" => "nullable | string",
            "discount_fix_amount" => "nullable | numeric",
            "expend_limit" => "nullable | numeric",
            "is_expend_limit" => "nullable | boolean",
            "is_fix_amount" => "nullable | boolean",
            "start_date" => "nullable | datetime",
            "end_date" => "nullable | datetime",
            "status" => "nullable | string | in:$memberDiscountStatusEnum"
        ];
    }
}
