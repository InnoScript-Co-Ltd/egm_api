<?php

namespace App\Http\Requests\Dashboard;

use App\Enums\RepaymentStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class DashboardRepaymentUpdateRequest extends FormRequest
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
        $repaymentStatus = implode(',', (new Enum(RepaymentStatusEnum::class))->values());

        return [
            'date' => 'nullable | date',
            'amount' => 'nullable | numeric',
            'total_amount' => 'nullable | numeric',
            'oneday_amount' => 'nullable | numeric',
            'count_days' => 'nullable | numeric',
            'total_days' => 'nullable | numeric',
            'status' => "nullable | in:$repaymentStatus",
            'created_at' => 'nullable | datetime',
            'updated_at' => 'nullable | datetime',
        ];
    }
}
