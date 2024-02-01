<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberDiscountStoreRequest extends FormRequest
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
        return [
            "label" => "required | string | unique:member_discounts,label",
            "discount_percentage" => "nullable | string",
            "discount_fix_amount" => "nullable | numeric",
            "expend_limit" => "nullable | numeric",
            "is_expend_limit" => "nullable | boolean",
            "is_fix_amount" => "nullable | boolean",
            "start_date" => "nullable | datetime",
            "end_date" => "nullable | datetime",
        ];
    }
}
