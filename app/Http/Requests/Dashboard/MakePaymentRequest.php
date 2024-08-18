<?php

namespace App\Http\Requests\Dashboard;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;

class MakePaymentRequest extends FormRequest
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
        $transactionIds = implode(',', Transaction::pluck('id')->toArray());

        return [
            'transaction_id' => "required | in:$transactionIds",
        ];
    }
}
