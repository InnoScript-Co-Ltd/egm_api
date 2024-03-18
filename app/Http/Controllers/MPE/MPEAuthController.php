<?php

namespace App\Http\Controllers\MPE;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\EamilVerifyRequest;
use App\Http\Requests\EmailVerifyCodeResendRequest;
use App\Http\Requests\MPEUserLoginRequest;
use App\Http\Requests\MPEUserRegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\EmailVerifyCode;
use App\Models\MPEUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Mail;

class MPEAuthController extends Controller
{
    public function login(MPEUserLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = MPEUser::where(['email' => $payload['email']])->first();

            if (! $user) {
                return $this->badRequest('Account does not found');
            }

            if ($user['status'] === 'PENDING') {
                return $this->badRequest('Account is not verified');
            }

            $token = auth()->guard('mpe')->attempt($payload->toArray());

            DB::commit();

            if ($token) {
                return $this->createNewToken($token);
            }

            return $this->badRequest('Incorrect email and passwrod');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function register(MPEUserRegisterRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $payload['email_verify_code'] = rand(100000, 999999);
            $payload['email_expired_at'] = Carbon::now()->addMinutes(5);

            $mpeUser = MPEUser::create($payload->toArray());
            Mail::to($payload['email'])->send(new EmailVerifyCode($payload['email_verify_code']));
            DB::commit();

            return $this->success('mpe user is successfully created', $mpeUser);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function emailVerify(EamilVerifyRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = MPEUser::findOrFail($payload['user_id']);

            $verifyCode = $user['email_verify_code'];
            $expiredAt = $user['email_expired_at'];

            if ($payload['verify_type'] === 'APPROVE' && $user['email_verified_at']) {
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

            if ($payload['verify_type'] === 'APPROVE') {
                $updatePayload['status'] = 'ACTIVE';
            }

            $user->update($updatePayload);
            DB::commit();

            return $this->success('your email is successfully verified', null);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function resendEmailVerifyCode(EmailVerifyCodeResendRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = MPEUser::where(['email' => $payload['email']])->first();

            $updatePayload['email_verify_code'] = rand(100000, 999999);
            $updatePayload['email_expired_at'] = Carbon::now()->addMinutes(5);
            $user->update($updatePayload);

            Mail::to($payload['email'])->send(new EmailVerifyCode($updatePayload['email_verify_code']));
            DB::commit();

            return $this->success('email verify code is successfully send', $updatePayload);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = MPEUser::findOrFail($payload['user_id']);
            $user->update($payload->toArray());
            DB::commit();

            return $this->success('password is sucessfully reset', null);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function logout()
    {
        DB::beginTransaction();

        try {
            $user = auth()->guard('mpe')->user();
            DB::commit();

            if ($user) {
                auth()->guard('mpe')->logout();

                return $this->success('User successfully signed out', null);
            }

            return $this->badRequest('Invalid token for logout');

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    protected function createNewToken($token)
    {
        return $this->success('User successfully signed in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('mpe')->factory()->getTTL() * 60,
            'user' => auth('mpe')->user(),
        ]);
    }
}
