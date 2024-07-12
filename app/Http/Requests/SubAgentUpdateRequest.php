<?php

namespace App\Http\Requests;

use App\Enums\AgentStatusEnum;
use App\Enums\REGXEnum;
use App\Helpers\Enum;
use App\Models\Agent;
use App\Models\SubAgent;
use Illuminate\Foundation\Http\FormRequest;

class SubAgentUpdateRequest extends FormRequest
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
        $subAgent = SubAgent::findOrFail(request('id'));
        $subAgentId = $subAgent->id;
        $agentIds = implode(',', Agent::pluck('id')->toArray());
        $mobileRule = REGXEnum::MOBILE_NUMBER->value;
        $agentStatus = implode(',', (new Enum(AgentStatusEnum::class))->values());

        return [
            'agent_id' => "nullable | string | in:$agentIds",
            'first_name' => 'nullable | string',
            'last_name' => 'nullable | string',
            'nrc' => "nullable | string | unique:sub_agents,nrc,$subAgentId",
            'nrc_front' => 'nullable | file',
            'nrc_back' => 'nullable | file',
            'phone' => ['nullable', 'string', "unique:sub_agents,phone,$subAgentId", "regex:$mobileRule"],
            'email' => "nullable | email | unique:sub_agents,email,$subAgentId",
            'roi_rate' => 'nullable | string',
            'stauts' => "nullable | string | in:$agentStatus",
        ];
    }
}
