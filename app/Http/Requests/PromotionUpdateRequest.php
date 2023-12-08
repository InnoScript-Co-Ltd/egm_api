<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class PromotionUpdateRequest extends FormRequest
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
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            'title' => 'string | nullable',
            'image' => 'numeric | nullable',
            'url' => 'string | nullable',
            'status' => "in:$generalStatusEnum | nullable",
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Please enter your promotion title',
            'title.stirng' => 'Please enter title using letters only in the title field.',
            'image.required' => 'Please enter your promotion image',
            'url.string' => 'Please check your promotion url must be string',
        ];
    }
}
