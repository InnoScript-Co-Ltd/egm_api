<?php

namespace App\Http\Requests\Agents;

use App\Enums\AgentStatusEnum;
use App\Enums\AgentTypeEnum;
use App\Enums\KycStatusEnum;
use App\Models\Agent;
use App\Models\AgentChannel;
use Illuminate\Foundation\Http\FormRequest;

class AgentInChannelStoreRequest extends FormRequest
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
        $agentIds = implode(',', Agent::where([
            'agent_type' => AgentTypeEnum::SUB_AGENT->value,
            'kyc_status' => KycStatusEnum::FULL_KYC->value,
            'status' => AgentStatusEnum::ACTIVE->value,
        ])->pluck('id')->toArray());

        $channelIds = implode(',', AgentChannel::pluck('id')->toArray());

        return [
            'agent_id' => "required | in:$agentIds",
            'channel_id' => "required | in:$channelIds",
            'percentage' => 'required | numeric',
        ];
    }
}
