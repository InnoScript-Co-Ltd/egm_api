<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Enums\KycStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AccountUpdateRequest;
use App\Http\Requests\Agents\AgentAccountUpdateRequest;
use App\Http\Requests\Agents\AgentEmailVerifyCodeRequest;
use App\Http\Requests\Agents\AgentEmailVerifyRequest;
use App\Http\Requests\Agents\AgentKycUpdateRequest;
use App\Mail\EmailVerifyCode;
use App\Models\Agent;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Mail;

class AccountController extends Controller
{
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

                // Mail::to($payload['email'])->send(new EmailVerifyCode($updatePayload['email_verify_code']));
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

    public function update(AccountUpdateRequest $request, $id)
    {

        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $agent = Agent::findOrFail($id);

            if (isset($payload['profile'])) {
                $profileImagePath = $payload['profile']->store('images', 'public');
                $profileImage = explode('/', $profileImagePath)[1];
                $payload['profile'] = $profileImage;
            }

            $agent->update($payload->toArray());
            DB::commit();

            return $this->success('Agent profile is updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function kycUpdate(AgentKycUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $agent = Agent::findOrFail($id);

            if ($agent->status !== AgentStatusEnum::ACTIVE->value) {
                DB::commit();

                return $this->badRequest('your account is not active');
            }

            if ($agent->status === AgentStatusEnum::ACTIVE->value && $agent->kyc_status === KycStatusEnum::FULL_KYC->value) {
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

    public function accountUpdate(AgentAccountUpdateRequest $request, $id)
    {
        $agent = auth('agent')->user();
        $agentId = $agent->id;

        if ($agent->status === AgentStatusEnum::ACTIVE->value) {
            $payload = collect($request->validated());
            DB::beginTransaction();

            try {
                $agent = Agent::findOrFail($agentId);
                $agent->update($payload->toArray());
                DB::commit();

                return $this->success('Agent account is updated successfully', $agent);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now');
    }
}
