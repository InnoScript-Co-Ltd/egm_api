<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentTypeEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AgentChannelStoreRequest;
use App\Http\Requests\Agents\AgentChannelUpdateRequest;
use App\Models\AgentChannel;
use App\Models\AgentInChannel;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentChannelController extends Controller
{
    public function index()
    {
        $auth = auth('agent')->user();
        DB::beginTransaction();
        try {
            $agentChannel = AgentChannel::with(['agentInChannel'])->where(['agent_id' => $auth->id])->get();
            DB::commit();

            return $this->success('agent channel list is successfully retrived', $agentChannel);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(AgentChannelStoreRequest $payload)
    {
        $auth = auth('agent')->user();

        DB::beginTransaction();

        try {
            $nameIn = AgentChannel::where([
                'agent_id' => $auth->id,
                'name' => $payload['name'],
            ])->get();

            if (count($nameIn) > 0) {
                return $this->badRequest('Channel name is already exist!');
            }

            $payload['agent_id'] = $auth->id;
            $agentChannel = AgentChannel::create($payload->toArray());
            DB::commit();

            return $this->success('agent channel is successfully created', $agentChannel);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(AgentChannelUpdateRequest $payload, $id)
    {
        $auth = auth('agent')->user();

        $nameIn = AgentChannel::where([
            'agent_id' => $auth->id,
            'name' => $payload['name'],
        ])->get();

        if (count($nameIn) > 0) {
            return $this->badRequest('Channel name is already exist!');
        }

        $subAgentIn = AgentInChannel::where(['channel_id' => $id])->get();

        if (count($subAgentIn) > 0) {
            return $this->badRequest('channel does not allow to updated');
        }

        if ($auth->agent_type === AgentTypeEnum::MAIN_AGENT->value) {

            DB::beginTransaction();

            try {
                $agentChannel = AgentChannel::findOrFail($id);
                $agentChannel->update($payload->toArray());
                DB::commit();

                return $this->success('agent channel is successfully updated', $agentChannel);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }

        } else {
            return $this->badRequest('You does not have permission to update channel');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $agentChannel = AgentChannel::findOrFail($id);
            $agentChannel->delete();
            DB::commit();

            return $this->success('agent channel is successfully deleted', $agentChannel);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
