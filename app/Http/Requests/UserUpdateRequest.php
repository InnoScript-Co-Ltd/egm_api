<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Enums\UserStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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

        return [
            'name' => 'required | string | max: 24 | min: 8',
            'profile' => 'string | nullable',
            'email' => 'nullable | email',
            'phone' => ['nullable', "regex:$mobileRule"],
            'password' => 'nullable | max: 24 | min: 6',
            'confirm_password' => 'required_with:password|same:password|min:6',
            'status' => "nullable | in:$userStatusEnum",
        ];
    }
}
