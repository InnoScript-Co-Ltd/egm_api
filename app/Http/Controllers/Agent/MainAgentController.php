<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Enums\AgentTypeEnum;
use App\Enums\KycStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\MainAgentStoreRequest;
use App\Mail\EmailVerifyCode;
use App\Models\Agent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Mail;

class MainAgentController extends Controller
{
    public function referenceLink()
    {
        try {
            $mainAgent = auth('agent')->user();
            if (
                $mainAgent->agent_type === AgentTypeEnum::MAIN_AGENT->value &&
                $mainAgent->status === AgentStatusEnum::ACTIVE->value &&
                $mainAgent->kyc_status === KycStatusEnum::FULL_KYC->value
            ) {
                $referenceLink['main_agent_id'] = $mainAgent->id;
                $referenceLink['reference_id'] = $mainAgent->id;
                $referenceLink['partner_id'] = $mainAgent->partner_id;
                $referenceLink['expired_at'] = Carbon::now()->addMonths(6);

                $token = Crypt::encrypt(json_encode($referenceLink));

                return $this->success('Reference link is generated successfully', $token);
            }

            return $this->badRequest('Reference link is generated fail');

        } catch (Exception $e) {
            throw $e;
        }

    }

    public function store(MainAgentStoreRequest $request)
    {
        if (request('reference')) {
            $payload = collect($request->validated());
            DB::beginTransaction();

            $refrenceLink = request('reference');
            $refrenceData = json_decode(Crypt::decrypt($refrenceLink));

            $payload['partner_id'] = $refrenceData->partner_id;
            $payload['agent_type'] = AgentTypeEnum::MAIN_AGENT->value;

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

            $payload['email_verify_code'] = rand(100000, 999999);
            $payload['email_expired_at'] = Carbon::now()->addMinutes(5);

            // Mail::to($payload['email'])->send(new EmailVerifyCode($payload['email_verify_code']));

            try {
                $mainAgent = Agent::create($payload->toArray());
                DB::commit();

                return $this->success('Main agent is created successfully', $mainAgent);
            } catch (Exception $e) {
                throw $e;
            }
        }

        return $this->badRequest('Reference token is required');
    }
}
