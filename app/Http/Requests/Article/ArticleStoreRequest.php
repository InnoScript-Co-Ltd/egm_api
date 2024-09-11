<?php

namespace App\Http\Requests\Article;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleStoreRequest extends FormRequest
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
            'article_type_id' => [
                'required',
                'integer',
                'exists:article_types,id',
            ],
            'language' => [
                'nullable',
                'string',
                'min: 2',
                'max: 10',
            ],
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('articles', 'title'),
            ],
            'description' => [
                'required',
                'string',
                'min: 10',
            ],
            'content' => [
                'required',
                'string',
                'min: 10',
            ],
            'photos' => [
                'required',
                'array',
                'min:1',
            ],
            'photos.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,gif',
                'max:2048',
            ],
            'status' => [
                'required',
                'string',
                Rule::in(Enum::make(GeneralStatusEnum::class)->values()),
            ],
        ];
    }
}
