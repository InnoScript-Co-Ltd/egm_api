<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Enums\UserStatusEnum;
use App\Helpers\Enum;
use App\Models\User;
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
        $userId = User::findOrFail(request('id'))->id();

        return [
            'name' => 'string | max: 24 | min: 8',
            'profile' => 'nullable',
            'email' => 'email | unique:users,email',
            'phone' => ['unique:users,phone', "regex:$mobileRule"],
            'password' => 'max: 24 | min: 6',
            'confirm_password' => 'required_with:password|same:password|min:6',
            'status' => "nullable | in:$userStatusEnum",
        ];
    }
}
