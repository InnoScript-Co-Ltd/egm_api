<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Enums\AgentTypeEnum;
use App\Enums\KycStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AccountReferenceLinkRequest;
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
    public function referenceLink(AccountReferenceLinkRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {

            $subAgent = Agent::findOrFail($payload['agent_id']);

            if (
                $subAgent->agent_type === AgentTypeEnum::SUB_AGENT->value &&
                $subAgent->status === AgentStatusEnum::ACTIVE->value &&
                $subAgent->kyc_status === KycStatusEnum::FULL_KYC->value
            ) {
                $referenceLink['main_agent_id'] = $subAgent->main_agent_id;
                $referenceLink['reference_id'] = $subAgent->id;
                $referenceLink['partner_id'] = $subAgent->partner_id;
                $referenceLink['expired_at'] = Carbon::now()->addMonths(6);

                $token = Crypt::encrypt(json_encode($referenceLink));

                DB::commit();

                return $this->success('Reference link is generated successfully', $token);
            }

            DB::commit();

            return $this->badRequest('Reference link is generated fail');

        } catch (Exception $e) {
            DB::rollBack();
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

            $levelTwoRefrenceAgent = Agent::findOrFail($refrenceData->reference_id);

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
            // Mail::to($payload['email'])->send(new EmailVerifyCode($payload['email_verify_code']));

            $agent = Agent::create($payload->toArray());
            $agentUpdate = Agent::findOrFail($agent->id);
            $agentUpdate->update(['level_one' => [$agent->id]]);

            if ($levelTwoRefrenceAgent['agent_type'] === AgentTypeEnum::MAIN_AGENT->value) {
                DB::commit();

                return $this->success('Agent is successfully created', $agent);
            }

            if ($levelTwoRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelTwoRefrenceAgent['level_two'] === null) {
                $levelTwoAgentPayload = [$agent->id];
            }

            if ($levelTwoRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelTwoRefrenceAgent['level_two'] !== null && count($levelTwoRefrenceAgent['level_two']) > 0) {
                $levelTwoAgent = $levelTwoRefrenceAgent->toArray();
                array_push($levelTwoAgent['level_two'], $agent->id);
                $levelTwoAgentPayload = $levelTwoAgent['level_two'];
            }

            $levelTwoRefrenceAgent->update(['level_two' => $levelTwoAgentPayload]);

            if ($levelTwoRefrenceAgent->reference_id !== null) {
                $levelThreeRefrenceAgent = Agent::findOrFail($levelTwoRefrenceAgent->reference_id);
            }

            $levelThreeAgentPayload = [];

            if ($levelThreeRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelThreeRefrenceAgent['level_three'] === null) {
                $levelThreeAgentPayload = [$agent->id];
            }

            if ($levelThreeRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelThreeRefrenceAgent['level_three'] !== null && count($levelThreeRefrenceAgent['level_three']) > 0) {
                $levelThreeAgent = $levelThreeRefrenceAgent->toArray();
                array_push($levelThreeAgent['level_three'], $agent->id);
                $levelThreeAgentPayload = $levelThreeAgent['level_three'];
            }

            $levelThreeRefrenceAgent->update(['level_three' => $levelThreeAgentPayload]);
            $levelFourRefrenceAgent = null;

            if ($levelThreeRefrenceAgent->reference_id !== null) {
                $levelFourRefrenceAgent = Agent::findOrFail($levelThreeRefrenceAgent->reference_id);
                $levelFourAgentPayload = [];

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

            DB::commit();

            return $this->success('Agent is successfully created', $agent);
        }
    }
}
