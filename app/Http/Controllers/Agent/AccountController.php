<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Enums\AgentTypeEnum;
use App\Enums\KycStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AccountStoreRequest;
use App\Http\Requests\Agents\AccountUpdateRequest;
use App\Http\Requests\Agents\AgentAccountUpdateRequest;
use App\Http\Requests\Agents\AgentEmailVerifyCodeRequest;
use App\Http\Requests\Agents\AgentEmailVerifyRequest;
use App\Http\Requests\Agents\AgentKycUpdateRequest;
use App\Mail\EmailVerifyCode;
use App\Models\Agent;
use App\Models\Referral;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mail;

class AccountController extends Controller
{
    public function store(AccountStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $referralLink = Referral::where(['link' => $payload['referral']])->get()->toArray();

            if (count($referralLink) > 0) {
                $referralAgent = $referralLink[0];

                $payload['main_agent_id'] = $referralAgent['main_agent_id'];
                $payload['reference_id'] = $referralAgent['reference_id'];
                $payload['partner_id'] = $referralAgent['partner_id'];
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

                $levelOneRefrenceAgent = Agent::findOrFail($referralAgent['reference_id']);

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

                $updateReferralAgent['count'] = $referralAgent['count'] + 1;
                Referral::where(['id' => $referralAgent['id']])->update($updateReferralAgent);
                DB::commit();

                return $this->success('Agent is successfully created', $agent);
            }

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

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

    public function update(AccountUpdateRequest $request)
    {
        $agent = auth('agent')->user();
        $payload = collect($request->validated());

        if ($agent && $agent->status === AgentStatusEnum::ACTIVE->value) {
            DB::beginTransaction();
            try {
                if (isset($payload['profile'])) {
                    $profileImagePath = $payload['profile']->store('images', 'public');
                    $profileImage = explode('/', $profileImagePath)[1];
                    $payload['profile'] = $profileImage;
                }

                $agent->update($payload->toArray());
                DB::commit();

                return $this->success('Agent profile is updated successfully');
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You account is not active');
    }

    public function kycUpdate(AgentKycUpdateRequest $request)
    {
        $agent = auth('agent')->user();
        $payload = collect($request->validated());

        if ($agent && $agent->status === AgentStatusEnum::ACTIVE->value) {
            DB::beginTransaction();

            try {
                if ($agent->kyc_status === KycStatusEnum::FULL_KYC->value) {
                    DB::commit();

                    return $this->badRequest('KYC is already active');
                }

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

                $agent->update($payload->toArray());
                DB::commit();

                return $this->success('Agent kyc is updated successfully', $agent);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        return $this->badRequest('Your account is not active');
    }

    public function accountUpdate(AgentAccountUpdateRequest $request)
    {
        $agent = auth('agent')->user();
        $payload = collect($request->validated());

        if ($agent->status === AgentStatusEnum::ACTIVE->value) {
            DB::beginTransaction();

            try {
                $agent->update($payload->toArray());
                DB::commit();

                return $this->success('Agent account is updated successfully', $agent);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        return $this->badRequest('Your account is not active');
    }

    public function generateLink()
    {
        $agent = auth('agent')->user();

        DB::beginTransaction();

        try {
            if ($agent->status === AgentStatusEnum::ACTIVE->value && $agent->kyc_status === KycStatusEnum::FULL_KYC->value) {

                $linkArray = explode('-', Str::uuid());
                $link = implode('', $linkArray);

                $referral = Referral::create([
                    'main_agent_id' => $agent->main_agent_id,
                    'reference_id' => $agent->id,
                    'partner_id' => $agent->partner_id,
                    'agent_id' => $agent->id,
                    'agent_type' => $agent->agent_type === AgentTypeEnum::MAIN_AGENT->value ? AgentTypeEnum::SUB_AGENT->value : $agent->agent_type,
                    'expired_at' => Carbon::now()->addMonths(1),
                    'link' => strtoupper($link),
                    'count' => 0,
                ]);

                DB::commit();

                return $this->success('Reference link is generated successfully', $referral);
            }

            DB::commit();

            return $this->badRequest('Reference link is generated fail');

        } catch (Exception $e) {
            throw $e;
        }
    }
}
