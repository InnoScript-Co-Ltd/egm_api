<?php

namespace App\Http\Requests\Agents;

use App\Enums\REGXEnum;
use App\Models\Agent;
use Illuminate\Foundation\Http\FormRequest;

class InvestorStoreRequest extends FormRequest
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

        $mobileRule = REGXEnum::MOBILE_NUMBER->value;
        $agentsIds = implode(',', Agent::pluck('id')->toArray());

        return [
            'agent_id' => "required | in:$agentsIds",
            'first_name' => 'required | string | min:3 | max:18',
            'last_name' => 'required | string | min:3 | max:18',
            'phone' => ['required', 'string', 'unique:investors,phone', "regex:$mobileRule"],
            'email' => 'required | string | unique:investors,email',
            'dob' => 'required | date',
            'nrc' => 'nullable | string | unique:investors,nrc',
            'nrc_front' => 'nullable | file',
            'nrc_back' => 'nullable | file',
            'address' => 'required | string',
        ];
    }
}
