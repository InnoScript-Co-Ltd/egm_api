<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class DashboardPartnerStoreRequest extends FormRequest
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
            'sender_id' => 'required | string',
            'sender_account_id' => 'required',
            'merchant_account_id' => 'required',
            'sender_account_name' => 'nullable | string',
            'sender_account_number' => 'nullable | string',
            'sender_bank_branch' => 'nullable | string',
            'sender_bank_address' => 'nullable | string',
            'merchant_account_name' => 'nullable | string',
            'merchant_account_number' => 'nullable | string',
            'bank_type' => 'nullable | string',
            'package_name' => 'nullable | string',
            'package_roi_rate' => 'nullable | numeric',
            'package_duration' => 'nullable | numeric',
            'package_deposit_amount' => 'nullable | numeric',
            'transaction_screenshoot' => 'nullable | file',
            'transaction_type' => 'nullable | string',
            'expired_at' => 'nullable | date',
            'status' => 'nullable | string',
            'created_at' => 'nullable | date',
            'updated_at' => 'nullable | date',
            'sender_type' => 'nullable | string',
        ];
    }
}
