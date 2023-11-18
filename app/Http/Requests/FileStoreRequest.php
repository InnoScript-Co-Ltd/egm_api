<?php

namespace App\Http\Requests;

use App\Enums\ValidationEnum;
use Illuminate\Foundation\Http\FormRequest;

class FileStoreRequest extends FormRequest
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
        $mimes = ValidationEnum::IMAGE->value;

        return [
            'file' => "required|mimes:$mimes|max:2048",
            'category' => 'required | string',
        ];

    }
}
