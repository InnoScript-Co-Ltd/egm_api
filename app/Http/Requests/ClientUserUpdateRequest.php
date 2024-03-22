<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Enums\REGXEnum;
use Illuminate\Foundation\Http\FormRequest;

class ClientUserUpdateRequest extends FormRequest
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
        $user = User::findOrFail(request('id'));
        $userId = $user->id;

        return [
            'name' => 'nullable|string',
            'email' => "nullable | email | unique:users,email,$userId",
            'phone' => ['nullable', "regex:$mobileRule", "unique:users,phone,$userId"],
            'dob' => 'nullable | date',
            'profile' => 'nullable | file'
        ];
    }
}
