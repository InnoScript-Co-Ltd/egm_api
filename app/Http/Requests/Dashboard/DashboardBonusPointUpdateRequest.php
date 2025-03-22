<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\GeneralStatusEnum;
use App\Helpers\Enum;
use App\Models\BonusPoint;
use Illuminate\Foundation\Http\FormRequest;

class DashboardBonusPointUpdateRequest extends FormRequest
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
        $bonusPoint = BonusPoint::findOrFail(request()->id);
        $bonusPointId = $bonusPoint->id;
        $generalStatus = implode(',', (new Enum(GeneralStatusEnum::class))->values());

        return [
            'label' => "nullable | string | unique:bonus_points,label,$bonusPointId",
            'limit_amount' => 'nullable | numeric',
            'status' => "nullable | string | in:$generalStatus",
        ];
    }
}
