<?php

namespace App\Http\Controllers\Client;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\ClientLoginRequest;
use App\Http\Requests\ClientRegisterRequest;
use App\Http\Requests\EamilVerifyRequest;
use App\Http\Requests\EmailVerifyCodeResendRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use App\Mail\EmailVerifyCode;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Mail;

class ClientAuthController extends Controller
{
    protected function createNewToken($token)
    {
        $auth = auth('api');

        return $this->success('User is successfully signed in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
            'user' => $auth->user(),
        ]);
    }

    public function login(ClientLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = User::where(['email' => $payload['email']])->first();

            if (!$user) {
                return $this->badRequest('Account does not found');
            }

            if ($user->status !== UserStatusEnum::ACTIVE->value) {
                return $this->badRequest('Account is not active');
            }
            $token = auth()->guard('api')->attempt($payload->toArray());

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

    public function register(ClientRegisterRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $payload['email_verify_code'] = rand(100000, 999999);
            $payload['email_expired_at'] = Carbon::now()->addMinutes(5);

            $user = User::create($payload->toArray());
            Mail::to($payload['email'])->send(new EmailVerifyCode($payload['email_verify_code']));
            DB::commit();

            return $this->success('User is successfully created', $user);

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
            $user = User::findOrFail($payload['user_id']);

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
            $user = User::where(['email' => $payload['email']])->first();

            $updatePayload['email_verify_code'] = rand(100000, 999999);
            $updatePayload['email_expired_at'] = Carbon::now()->addMinutes(5);
            $user->update($updatePayload);

            Mail::to($payload['email'])->send(new EmailVerifyCode($updatePayload['email_verify_code']));
            DB::commit();

            return $this->success('email verify code is resend successfully', $updatePayload);
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
            $user = User::findOrFail($payload['user_id']);
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
            $user = auth()->guard('api')->user();
            DB::commit();

            if ($user) {
                auth()->guard('api')->logout();

                return $this->success('User successfully signed out', null);
            }

            return $this->badRequest('Invalid token for logout');

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
