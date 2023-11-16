<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class DeliveryAddressStoreRequest extends FormRequest
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
            'user_id' => "in:$userId | required",
            'address' => 'string | required',
            'contact_phone' => ['required', 'string', "regex:$mobileRule"],
            'contact_person' => 'string | required',
        ];
    }
}
