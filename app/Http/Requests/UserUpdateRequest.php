<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Enums\UserStatusEnum;
use App\Helpers\Enum;
use App\Models\User;
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
        $user = User::findOrFail(request('id'));
        $userId = $user->id;

        return [
            'name' => 'string | max: 24 | min: 8',
            'profile' => 'nullable | image:mimes:jpeg,png,jpg,gif|max:2048',
            'email' => "nullable | email | unique:users,email,$userId",
            'phone' => ['nullable', "regex:$mobileRule", "unique:users,phone,$userId"],
            'gender' => 'nullable | string',
            'occupation' => 'nullable | string',
            'position' => 'nullable | string',
            'address' => 'nullable | string',
            'dob' => 'nullable | date',
            'status' => "nullable | in:$userStatusEnum",
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Please enter your name using letters only in the name field.',
            'name.max' => 'Please keep your input within 24 letter.',
            'phone.regex' => 'Please provide your phone number will start only 9xxxxxxx.',
            'dob.date' => 'Invalid date format',
        ];
    }
}
