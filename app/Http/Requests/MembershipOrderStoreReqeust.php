<?php

namespace App\Http\Requests;

use App\Models\Member;
use App\Models\User;
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
        $users = implode(',', User::all()->pluck('id')->toArray());

        return [
            'member_id' => "required | in:$members",
            'user_id' => "required | in:$users",
            'order_number' => 'required | string',
            'amount' => 'required | numeric',
        ];
    }
}
