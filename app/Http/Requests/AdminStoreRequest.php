<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Models\File;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class AdminStoreRequest extends FormRequest
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
        $fileIds = implode(',', File::all()->pluck('id')->toArray());
        $roleIds = implode(',', Role::all()->pluck('id')->toArray());

        return [
            'name' => 'required | string | max: 24 | min: 8',
            'email' => 'required | email | unique:admins,email',
            'phone' => ['required', 'unique:admins,phone', "regex:$mobileRule"],
            'password' => 'required | max: 24 | min: 6',
            'confirm_password' => 'required_with:password|same:password|min:6',
            'profile' => "nullable | in:$fileIds",
            'role_id' => 'required',
            'role_id.*' => "in:$roleIds",
        ];
    }
}
