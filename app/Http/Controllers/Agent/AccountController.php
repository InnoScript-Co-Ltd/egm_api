<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AgentEmailVerifyCodeRequest;
use App\Http\Requests\Agents\AgentEmailVerifyRequest;
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
}
