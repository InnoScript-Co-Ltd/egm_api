<?php

namespace App\Http\Requests;

use App\Helpers\Enum;
use App\Enums\MemberDiscountStatus;
use App\Models\MemberDiscount;
use Illuminate\Foundation\Http\FormRequest;

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
        $memberDiscount = MemberDiscount::findOrFail(request('id'));
        $memberDiscountId = $memberDiscount->id;

        $memberDiscountStatusEnum = implode(',', (new Enum(MemberDiscountStatus::class))->values());

        return [
            'label' => "required | string | unique:member_discounts,label,$memberDiscountId",
            'discount_percentage' => 'nullable | string',
            'discount_fix_amount' => 'nullable | numeric',
            'expend_limit' => 'nullable | numeric',
            'is_expend_limit' => 'nullable | boolean',
            'is_fix_amount' => 'nullable | boolean',
            'start_date' => 'nullable | date_format:Y-m-d',
            'end_date' => 'nullable | date_format:Y-m-d',
            'status' => "nullable | string | in:$memberDiscountStatusEnum",
        ];
    }
}
