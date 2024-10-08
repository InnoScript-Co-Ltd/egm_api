<?php

namespace App\Http\Requests\Comment;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentUpdateRequest extends FormRequest
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
                'nullable',
                'integer',
                Rule::exists('article_types', 'id'),
            ],
            'article_id' => [
                'nullable',
                'integer',
                Rule::exists('articles', 'id'),
            ],
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'min: 10',
            ],
            'content' => [
                'nullable',
                'string',
                'min: 10',
            ],
            'photos' => [
                'nullable',
                'array',
                'min:1',
            ],
            'photos.*' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,gif',
                'max:2048',
            ],
            'status' => [
                'nullable',
                'string',
                Rule::in(Enum::make(GeneralStatusEnum::class)->values()),
            ],
        ];
    }
}
