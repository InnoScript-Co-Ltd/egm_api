<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentTypeEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AgentInChannelStoreRequest;
use App\Models\AgentInChannel;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentInChannelController extends Controller
{
    public function store(AgentInChannelStoreRequest $request)
    {
        $auth = auth('agent')->user();
        $payload = collect($request->validated());

        $channelIn = AgentInChannel::where([
            'main_agent_id' => $auth->id,
            'channel_id' => $payload['channel_id'],
            'agent_id' => $payload['agent_id'],
        ])->get();

        if (count($channelIn)) {
            return $this->badRequest('Sub agent is already exist in this channel');
        }

        if ($auth->agent_type === AgentTypeEnum::MAIN_AGENT->value) {

            DB::beginTransaction();
            try {
                $payload['main_agent_id'] = $auth->id;
                $agentInChannel = AgentInChannel::create($payload->toArray());
                DB::commit();

                return $this->success('Add new agent in channel is successfully', $agentInChannel);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }

        } else {
            return $this->badRequest('You does not have permission to add sub agent to channel');
        }
    }
}
