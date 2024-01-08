<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Enums\UserStatusEnum;
use App\Helpers\Enum;
use App\Models\File;
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
        $fileIds = implode(',', File::all()->pluck('id')->toArray());

        return [
            'name' => 'string | max: 24 | min: 8',
            'profile' => "nullable",
            'email' => 'nullable | email',
            'phone' => ['nullable', "regex:$mobileRule"],
            'password' => 'nullable | max: 24 | min: 6',
            'confirm_password' => 'required_with:password|same:password|min:6',
            'status' => "nullable | in:$userStatusEnum",
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Please enter your name using letters only in the name field.',
            'name.max' => 'Please keep your input within 24 letter.',
            'password.max' => 'Please keep your input within 24 letter.',
            'password.min' => 'Please password field at least 6 letter.',
            'phone.regex' => 'Please provide your phone number will start only 9xxxxxxx.',
        ];
    }
}
