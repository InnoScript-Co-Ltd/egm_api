<?php

namespace App\Http\Requests;

use App\Models\Member;
use Illuminate\Foundation\Http\FormRequest;

class MembershipOrderStoreReqeust extends FormRequest
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
        $members = implode(',', Member::all()->pluck('id')->toArray());

        return [
            'member_id' => "required | in:$members",
            'order_number' => 'required | string | unique:membership_orders,order_number',
            'amount' => 'required | numeric',
            'is_wallet' => 'required | boolean',
            'status' => 'required | string',
        ];
    }
}
