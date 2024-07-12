<?php

namespace App\Http\Requests;

use App\Enums\REGXEnum;
use App\Models\Agent;
use Illuminate\Foundation\Http\FormRequest;

class SubAgentStoreRequest extends FormRequest
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
        $agentIds = implode(',', Agent::pluck('id')->toArray());
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;

        return [
            'agent_id' => "required | string | in:$agentIds",
            'first_name' => 'required | string',
            'last_name' => 'required | string',
            'nrc' => 'nullable | string | unique:sub_agents,nrc',
            'nrc_front' => 'nullable | file',
            'nrc_back' => 'nullable | file',
            'phone' => ['required', 'string', 'unique:sub_agents,phone', "regex:$mobileRule"],
            'email' => 'nullable | email | unique:sub_agents,email',
            'roi_rate' => 'nullable | string',
        ];
    }
}
