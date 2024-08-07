<?php

namespace App\Http\Requests\Agents;

use App\Models\Agent;
use Illuminate\Foundation\Http\FormRequest;

class AgentChangePasswordRequest extends FormRequest
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
            'old_password' => 'required | string',
            'password' => 'required | string  | confirmed',
            'password_confirmation' => 'required | string',
        ];
    }
}
