<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class EmailContentStoreRequest extends FormRequest
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
        $countryId = implode(',', Country::pluck('id')->toArray());

        return [
            'country_id' => "required | in:$countryId",
            'template' => 'required | string',
            'content_type' => 'required | string',
            'title' => 'required | string',
            'content' => 'required | string',
        ];
    }
}
