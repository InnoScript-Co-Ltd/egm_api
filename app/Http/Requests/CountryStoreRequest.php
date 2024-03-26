<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CountryStoreRequest extends FormRequest
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
        return [
            'name' => 'required | string | unique:countries,name',
            'country_code' => 'required | string | min:2 | max:4 | unique:countries,country_code',
            'mobile_prefix' => 'required | string | unique:countries,mobile_prefix',
            'flag_image' => 'required |  image:mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
