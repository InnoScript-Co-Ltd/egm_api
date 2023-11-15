<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Helpers\Enum;
use App\Enums\GeneralStatusEnum;
use App\Models\Category;

class CategoryUpdateRequest extends FormRequest
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
        $categoryId = implode(',', Category::all()->pluck('id')->toArray());
        $generalStatusEnum = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            "title" => "nullable | string",
            "level" => "nullable | numeric",
            "category_id" => "nullable | in:$categoryId",
            "description" => "string",
            "status" => "nullable | in:$generalStatusEnum"
        ];
    }
}
