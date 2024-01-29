<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class MemberUpdateRequest extends FormRequest
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
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());
        $member = Member::findOrFail(request('id'));
        $memberIds = implode(',', Member::all()->pluck('id')->toArray());
        $memberId = $member['id'];

        return [
            'user_id' => "nullable | in:$userIds",
            'member_id' => "nullable | in:$memberIds, unique:members,member_id,$memberId",
            'amount' => 'nullable | numeric',
            'expired_at' => 'nullable | date_format:Y-m-d',
            'status' => "nullable | string | in:$generalStatusEnum",
        ];
    }
}
