<?php

namespace App\Http\Requests\ArticleLike;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleLikeStoreRequest extends FormRequest
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
                Rule::exists('article_types', 'id'),
            ],
            'article_id' => [
                'required',
                'integer',
                Rule::exists('articles', 'id'),
            ],
            'comment_id' => [
                'required',
                'integer',
                Rule::exists('comments', 'id'),
            ],
        ];
    }
}
