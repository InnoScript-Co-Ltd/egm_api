<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Enums\AgentTypeEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AccountReferenceLinkRequest;
use App\Http\Requests\Agents\AccountStoreRequest;
use App\Http\Requests\Agents\AgentEmailVerifyCodeRequest;
use App\Http\Requests\Agents\AgentEmailVerifyRequest;
use App\Mail\EmailVerifyCode;
use App\Models\Agent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Mail;

class AccountController extends Controller
{
    public function referenceLink(AccountReferenceLinkRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {

            $agent = Agent::findOrFail($payload['agent_id']);

            if ($agent->agent_type === AgentTypeEnum::MAIN_AGENT->value) {
                $referenceLink['main_agent_id'] = $agent->id;
                $referenceLink['reference_id'] = $agent->id;
                $token = Crypt::encrypt(json_encode($referenceLink));
            }

            if ($agent->agent_type === AgentTypeEnum::SUB_AGENT->value) {
                $referenceLink['main_agent_id'] = $agent->main_agent_id;
                $referenceLink['reference_id'] = $agent->id;

                $token = Crypt::encrypt(json_encode($referenceLink));
            }

            DB::commit();

            return $this->success('Reference link is generated successfully', $token);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function store(AccountStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        if (request('reference')) {
            $refrenceLinkData = request('reference');
            $refrenceData = json_decode(Crypt::decrypt($refrenceLinkData));

            $levelTwoRefrenceAgent = Agent::findOrFail($refrenceData->reference_id);

            $payload['main_agent_id'] = $refrenceData->main_agent_id;
            $payload['reference_id'] = $refrenceData->reference_id;

            $agent = Agent::create($payload->toArray());
            $agentUpdate = Agent::findOrFail($agent->id);

            $agentUpdate->update(['level_one' => [$agent->id]]);

            if ($levelTwoRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelTwoRefrenceAgent['level_two'] === null) {
                $levelTwoRefrenceAgent->update(['level_two' => [$agent->id]]);
            }

            if ($levelTwoRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && count($levelTwoRefrenceAgent['level_two']) > 0) {
                $levelTwoAgent = $levelTwoRefrenceAgent->toArray();
                array_push($levelTwoAgent['level_two'], $agent->id);
                $levelTwoRefrenceAgent->update(['level_two' => $levelTwoAgent['level_two']]);
            }

            $levelThreeRefrenceAgent = Agent::findOrFail($levelTwoRefrenceAgent->reference_id);

            if ($levelThreeRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelThreeRefrenceAgent['level_three'] === null) {
                $levelThreeRefrenceAgent->update(['level_three' => [$agent->id]]);
            }

            if ($levelThreeRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && count($levelThreeRefrenceAgent['level_three']) > 0) {
                $levelThreeAgent = $levelThreeRefrenceAgent->toArray();
                array_push($levelThreeAgent['level_three'], $agent->id);
                $levelThreeRefrenceAgent->update(['level_three' => $levelThreeAgent['level_three']]);
            }

            $levelFourRefrenceAgent = Agent::findOrFail($levelThreeRefrenceAgent->reference_id);

            if ($levelFourRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && $levelFourRefrenceAgent['level_four'] === null) {
                $levelFourRefrenceAgent->update(['level_four' => [$agent->id]]);
            }

            if ($levelFourRefrenceAgent['agent_type'] === AgentTypeEnum::SUB_AGENT->value && count($levelFourRefrenceAgent['level_four']) > 0) {
                $levelFourAgent = $levelFourRefrenceAgent->toArray();
                array_push($levelFourAgent['level_four'], $agent->id);
                $levelFourRefrenceAgent->update(['level_four' => $levelFourAgent['level_four']]);
            }

            DB::commit();

            return $this->success('Agent is successfully created', $agent);
        }
    }
    // public function test(AccountStoreRequest $request)
    // {
    //     $payload = collect($request->validated());
    //     DB::beginTransaction();

    //     try {
    //         $payload['email_verify_code'] = rand(100000, 999999);
    //         $payload['email_expired_at'] = Carbon::now()->addMinutes(5);

    //         $agent = Agent::create($payload->toArray());
    //         // Mail::to($payload['email'])->send(new EmailVerifyCode($payload['email_verify_code']));
    //         DB::commit();

    //         return $this->success('Agent is successfully created', $agent);

    //     } catch (Exception $e) {
    //         DB::rollBack();
    //         throw $e;
    //     }
    // }

    public function emailVerify(AgentEmailVerifyRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $agent = Agent::findOrFail($payload['agent_id']);

            $verifyCode = $agent['email_verify_code'];
            $expiredAt = $agent['email_expired_at'];

            if ($agent['email_verified_at']) {
                return $this->badRequest('account is already verified');
            }

            $now = Carbon::now();
            $checkExpired = Carbon::now()->between($now, $expiredAt);

            if ($checkExpired === false) {
                return $this->badRequest('Email verify code is expired');
            }

            if ($verifyCode !== $payload['email_verify_code']) {
                return $this->badRequest('Email verify code is does not match');
            }

            $updatePayload['email_verify_code'] = null;
            $updatePayload['email_verified_at'] = Carbon::now();
            $updatePayload['status'] = AgentStatusEnum::ACTIVE->value;

            $agent->update($updatePayload);
            DB::commit();

            return $this->success('your email is successfully verified', null);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function resendVerifyCode(AgentEmailVerifyCodeRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $agent = Agent::where(['email' => $payload['email']])->first();

            if ($agent !== null) {
                $updatePayload['email_verify_code'] = rand(100000, 999999);
                $updatePayload['email_expired_at'] = Carbon::now()->addMinutes(5);
                $agent->update($updatePayload);

                Mail::to($payload['email'])->send(new EmailVerifyCode($updatePayload['email_verify_code']));
                DB::commit();

                $updatePayload['agent_id'] = $agent->id;

                return $this->success('email verify code is resend successfully', $updatePayload);
            }

            return $this->badRequest('Your email could not be found');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
