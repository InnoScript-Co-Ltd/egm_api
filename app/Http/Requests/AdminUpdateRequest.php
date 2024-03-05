<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Enums\UserStatusEnum;
use App\Helpers\Enum;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateRequest extends FormRequest
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
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;
        $userStatusEnum = implode(',', (new Enum(UserStatusEnum::class))->values());
        $user = Admin::findOrFail(request('id'));
        $userId = $user->id;
        $roleIds = implode(',', Role::all()->pluck('id')->toArray());

        return [
            'id' => 'string',
            'name' => 'string | max: 24 | min: 4',
            'profile' => 'nullable | image:mimes:jpeg,png,jpg,gif|max:2048',
            'email' => "email | unique:users,email,$userId",
            'phone' => ["unique:users,phone,$userId", "regex:$mobileRule"],
            'dob' => 'nullable | date',
            'nrc' => 'nullable | string',
            'address' => 'nullable | string',
            'position' => 'nullable | string',
            'department' => 'nullable | string',
            'join_date' => 'nullable | date',
            'leave_date' => 'nullable | date',
            'salary' => 'nullable | numeric',
            'role_id' => 'nullable',
            'role_id.*' => "in:$roleIds",
            'status' => "nullable | in:$userStatusEnum",
        ];
    }
}
