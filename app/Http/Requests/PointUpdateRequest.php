<?php

namespace App\Http\Requests;

use App\Enums\PointLabelEnum;
use App\Helpers\Enum;
use App\Models\Point;
use Illuminate\Foundation\Http\FormRequest;

class PointUpdateRequest extends FormRequest
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
        $point = Point::findOrFail(request('id'));
        $pointId = $point->id;

        $pointLabelEnum = implode(',', (new Enum(PointLabelEnum::class))->values());

        return [
            'label' => ['string', "unique:points,label,$pointId", "in:$pointLabelEnum"],
            'point' => 'numeric',
        ];
    }
}
