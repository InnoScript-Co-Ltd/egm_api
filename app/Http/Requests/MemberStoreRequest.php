<?php

namespace App\Http\Requests;

use App\Models\MemberCard;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class MemberStoreRequest extends FormRequest
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
        $userIds = implode(',', User::all()->pluck('id')->toArray());
        $memberCardIds = implode(',', MemberCard::all()->pluck('id')->toArray());

        return [
            'user_id' => "required | in:$userIds",
            'membercard_id' => "required | in:$memberCardIds",
            'member_id' => 'required | unique:members,member_id',
            'amount' => 'nullable | numeric',
            'expired_at' => 'nullable | date',
        ];
    }
}
