<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Enums\AgentTypeEnum;
use App\Enums\KycStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\SubAgentStoreRequest;
use App\Mail\EmailVerifyCode;
use App\Models\Agent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Mail;

class SubAgentController extends Controller
{
    protected $showableForLevelAgent = [
        'id',
        'profile',
        'point',
        'first_name',
        'last_name',
        'dob',
        'nrc',
        'email',
        'phone',
        'address',
        'kyc_status',
        'status',
        'agent_type',
        'created_at',
        'updated_at',
    ];

    public function referenceLink()
    {
        $subAgent = auth('agent')->user();
        try {

            if (
                $subAgent->agent_type === AgentTypeEnum::SUB_AGENT->value &&
                $subAgent->status === AgentStatusEnum::ACTIVE->value &&
                $subAgent->kyc_status === KycStatusEnum::FULL_KYC->value
            ) {
                $referenceLink['main_agent_id'] = $subAgent->main_agent_id;
                $referenceLink['reference_id'] = $subAgent->id;
                $referenceLink['partner_id'] = $subAgent->partner_id;

                $token = Crypt::encrypt(json_encode($referenceLink));

                return $this->success('Reference link is generated successfully', $token);
            }

            return $this->badRequest('Reference link is generated fail');

        } catch (Exception $e) {
            throw $e;
        }

    }

    public function store(SubAgentStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        if (request('reference')) {
            $refrenceLinkData = request('reference');
            $refrenceData = json_decode(Crypt::decrypt($refrenceLinkData));

            if (! isset($refrenceData->reference_id)) {
                DB::commit();

                return $this->badRequest('Invalid refrence link');
            }

            $payload['main_agent_id'] = $refrenceData->main_agent_id;
            $payload['reference_id'] = $refrenceData->reference_id;
            $payload['partner_id'] = $refrenceData->partner_id;
            $payload['email_verify_code'] = rand(100000, 999999);
            $payload['email_expired_at'] = Carbon::now()->addMinutes(5);

            if (isset($payload['nrc_front'])) {
                $nrcFrontImagePath = $payload['nrc_front']->store('images', 'public');
                $nrcFrontImage = explode('/', $nrcFrontImagePath)[1];
                $payload['nrc_front'] = $nrcFrontImage;
            }

            if (isset($payload['nrc_back'])) {
                $nrcBackImagePath = $payload['nrc_back']->store('images', 'public');
                $nrcBackImage = explode('/', $nrcBackImagePath)[1];
                $payload['nrc_back'] = $nrcBackImage;
            }

            Mail::to($payload['email'])->send(new EmailVerifyCode($payload['email_verify_code']));

            $agent = Agent::create($payload->toArray());
            // $agentUpdate = Agent::findOrFail($agent->id);
            // $agentUpdate->update(['level_one' => [$agent->id]]);

            $levelOneRefrenceAgent = Agent::findOrFail($refrenceData->reference_id);

            /** Level 1 Agent Update */
            if ($levelOneRefrenceAgent['agent_type'] === AgentTypeEnum::MAIN_AGENT->value) {
                DB::commit();

                return $this->success('Agent is successfully created', $agent);
            }

            $levelOneAgentPayload = null;

            if ($levelOneRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelOneRefrenceAgent['level_one'] === null) {
                $levelOneAgentPayload = [$agent->id];
            }

            if ($levelOneRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelOneRefrenceAgent['level_one'] !== null && count($levelOneRefrenceAgent['level_one']) > 0) {
                $levelOneAgent = $levelOneRefrenceAgent->toArray();
                array_push($levelOneAgent['level_one'], $agent->id);
                $levelOneAgentPayload = $levelOneAgent['level_one'];
            }

            $levelOneRefrenceAgent->update(['level_one' => $levelOneAgentPayload]);

            /** Level 2 Agent Update */
            if ($levelOneRefrenceAgent->reference_id !== null) {
                $levelTwoRefrenceAgent = Agent::findOrFail($levelOneRefrenceAgent->reference_id);
            }

            $levelTwoAgentPayload = null;

            if ($levelTwoRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelTwoRefrenceAgent['level_two'] === null) {
                $levelTwoAgentPayload = [$agent->id];
            }

            if ($levelTwoRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelTwoRefrenceAgent['level_two'] !== null && count($levelTwoRefrenceAgent['level_two']) > 0) {
                $levelTwoAgent = $levelTwoRefrenceAgent->toArray();
                array_push($levelTwoAgent['level_two'], $agent->id);
                $levelTwoAgentPayload = $levelTwoAgent['level_two'];
            }

            $levelTwoRefrenceAgent->update(['level_two' => $levelTwoAgentPayload]);

            /** Level 3 Agent Update */
            if ($levelTwoRefrenceAgent->reference_id !== null) {
                $levelThreeRefrenceAgent = Agent::findOrFail($levelTwoRefrenceAgent->reference_id);
                $levelThreeAgentPayload = null;

                if ($levelThreeRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelThreeRefrenceAgent['level_three'] === null) {
                    $levelThreeAgentPayload = [$agent->id];
                }

                if ($levelThreeRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelThreeRefrenceAgent['level_three'] !== null && count($levelThreeRefrenceAgent['level_three']) > 0) {
                    $levelThreeAgent = $levelThreeRefrenceAgent->toArray();
                    array_push($levelThreeAgent['level_three'], $agent->id);
                    $levelThreeAgentPayload = $levelThreeAgent['level_three'];
                }

                $levelThreeRefrenceAgent->update(['level_three' => $levelThreeAgentPayload]);

                /** Level 4 Agent Update */
                if ($levelThreeRefrenceAgent->reference_id !== null) {
                    $levelFourRefrenceAgent = Agent::findOrFail($levelThreeRefrenceAgent->reference_id);
                    $levelFourAgentPayload = null;

                    if ($levelFourRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelFourRefrenceAgent['level_four'] === null) {
                        $levelFourAgentPayload = [$agent->id];
                    }

                    if ($levelFourRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelFourRefrenceAgent['level_four'] !== null && count($levelFourRefrenceAgent['level_four']) > 0) {
                        $levelFourAgent = $levelFourRefrenceAgent->toArray();
                        array_push($levelFourAgent['level_four'], $agent->id);
                        $levelFourAgentPayload = $levelFourAgent['level_four'];
                    }

                    $levelFourRefrenceAgent->update(['level_four' => $levelFourAgentPayload]);
                }
            }

            DB::commit();

            return $this->success('Agent is successfully created', $agent);
        }
    }

    public function level($level)
    {
        DB::beginTransaction();

        try {

            $agent = auth('agent')->user();
            $agentIds = $agent[$level];

            if ($agentIds !== null) {
                $agents = Agent::select($this->showableForLevelAgent)
                    ->with(['deposit'])
                    ->whereIn('id', $agentIds)
                    ->get();

                DB::commit();

                return $this->success('Level one agents are retrived successfully', $agents);
            }

            DB::commit();

            return $this->success('Level one agents are retrived successfully', []);
        } catch (Exception $e) {
            DB::commit();
            throw $e;
        }
    }
}
