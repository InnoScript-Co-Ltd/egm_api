<?php

namespace App\Http\Requests;

use App\Models\MemberDiscount;
use Illuminate\Foundation\Http\FormRequest;

class MemberCardStoreRequest extends FormRequest
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
        $memberDiscounts = implode(',', MemberDiscount::all()->pluck('id')->toArray());

        return [
            'label' => 'required | string | unique:membercards,label',
            'discount_id' => "nullable | in:$memberDiscounts",
            'front_background' => 'nullable | file',
            'back_background' => 'nullable | file',
            'expired_at' => 'nullable | date_format:Y-m-d',
        ];
    }
}
