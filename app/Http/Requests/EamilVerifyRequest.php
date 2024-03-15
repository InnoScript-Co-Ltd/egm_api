<?php

namespace App\Http\Requests;

use App\Enums\VerifyStatusEnum;
use App\Helpers\Enum;
use Illuminate\Foundation\Http\FormRequest;

class EamilVerifyRequest extends FormRequest
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
        $verifyStatus = implode(',', (new Enum(VerifyStatusEnum::class))->values());

        return [
            'user_id' => 'required',
            'email_verify_code' => 'required | string | min:6 | max:6',
            'verify_type' => "required | string | in:$verifyStatus",
        ];
    }
}
