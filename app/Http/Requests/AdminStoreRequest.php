<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
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
        $roleIds = implode(',', Role::all()->pluck('id')->toArray());

        return [
            'name' => 'required | string',
            'email' => 'required | email | unique:admins,email',
            'phone' => ['required', 'unique:admins,phone', "regex:$mobileRule"],
            'dob' => 'nullable | date',
            'nrc' => 'nullable | string',
            'address' => 'nullable | string',
            'position' => 'nullable | string',
            'department' => 'nullable | string',
            'join_date' => 'nullable | date',
            'leave_date' => 'nullable | date',
            'salary' => 'nullable | numeric',
            'role_id' => 'required',
            'role_id.*' => "in:$roleIds",
        ];
    }
}
