<?php

namespace App\Http\Requests\Agents;

use App\Models\Agent;
use Illuminate\Foundation\Http\FormRequest;

class AgentEmailVerifyRequest extends FormRequest
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

        return [
            'agent_id' => "required | in:$agentIds",
            'email_verify_code' => 'required | string | min:6 | max:6',
        ];
    }
}
