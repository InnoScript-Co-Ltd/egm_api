<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;
use App\Enums\REGXEnum;

class DeliveryAddressUpdateRequest extends FormRequest
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
        $userId = implode(',', User::all()->pluck('id')->toArray());
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;

        return [
            "user_id" => "in:$userId | nullable",
            "address" => "string | nullable",
            "contact_phone" => [ 'nullable','string', "regex:$mobileRule"],
            "contact_person" => "string | nullable",
        ];
    }
}
