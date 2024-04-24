<?php

namespace App\Http\Requests;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\Promotion;
use Illuminate\Foundation\Http\FormRequest;

class PromotionItemUpdateRequest extends FormRequest
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
        Promotion::findOrFail(request('id'));

        return [
            'promotion_price' => 'nullable | numeric',
            'status' => "nullable | in:$generalStatusEnum",
        ];
    }
}
