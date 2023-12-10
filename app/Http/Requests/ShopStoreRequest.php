<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Models\Region;
use Illuminate\Foundation\Http\FormRequest;

class ShopStoreRequest extends FormRequest
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
        $regionId = implode(',', Region::all()->pluck('id')->toArray());
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;

        return [
            'region_id' => "required | in:$regionId",
            'name' => 'string',
            'phone' => ['nullable', 'string', "regex:$mobileRule"],
            'address' => 'string',
            'location' => 'string',
        ];
    }

    public function messages()
    {
        return [
            'region_id.required' => 'Please choose your region name',
            'name.string' => 'Please enter your name using letters only in the name field.',
            'phone.regex' => 'Please provide your phone number will start only 9xxxxxxx.',
            'address.string' => 'Please enter your address using letters only in the address field',
            'location.string' => 'Please enter your location using letters only in the address field',
        ];
    }
}
