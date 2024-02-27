<?php

namespace App\Http\Requests;

use App\Helpers\Enum;
use App\Enums\MemberCardStatusEnum;
use App\Models\MemberCard;
use App\Models\MemberDiscount;
use Illuminate\Foundation\Http\FormRequest;

class MemberCardUpdateRequest extends FormRequest
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
        $memberDiscounts = implode(',', MemberDiscount::all()->pluck('id')->toArray());
        $memberCard = MemberCard::find(request('id'));
        $memberCardId = $memberCard->id;
        $memberCardStatusEnum = implode(',', (new Enum(MemberCardStatusEnum::class))->values());

        return [
            'label' => "nullable | string",
            'discount_id' => "nullable | in:$memberDiscounts",
            'front_background' => 'nullable',
            'back_background' => 'nullable',
            'expired_at' => 'nullable | date_format:Y-m-d',
            'status' => "nullable | string | in:$memberCardStatusEnum",
        ];
    }
}
