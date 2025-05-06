<?php

namespace App\Http\Controllers\Partner;

use App\Enums\AgentStatusEnum;
use App\Enums\KycStatusEnum;
use App\Enums\PartnerStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\ConfrimPaymentPasswordRequest;
use App\Http\Requests\Partner\PartnerChangePasswordRequest;
use App\Http\Requests\Partner\PartnerForgetPasswordRequest;
use App\Http\Requests\Partner\PartnerLoginRequest;
use App\Http\Requests\Partner\PartnerPaymentPasswordUpdateRequest;
use App\Http\Requests\Partner\PartnerResetPassword;
use App\Http\Requests\Partner\PartnerVerifiedOtp;
use App\Models\Partner;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PartnerAuthController extends Controller
{
    protected function createNewToken($token)
    {
        $id = auth('partner')->user()->id;

        $user = Partner::findOrFail($id);

        return $this->success('Partner is signed in successfully', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }

    public function login(PartnerLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $partner = Partner::where(['email' => $payload['email']])->first();

            if (! $partner) {
                return $this->badRequest('Account does not found');
            }

            if ($partner->status !== PartnerStatusEnum::ACTIVE->value) {
                return $this->badRequest('Account is not active');
            }

            $token = auth()->guard('partner')->attempt($payload->toArray());

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

    public function profile()
    {
        $auth = auth('partner')->user();

        $partner = Partner::with(['deposit'])->findOrFail($auth->id);
        $partner['make_payment'] = $partner->payment_password !== null ? true : false;

        return $this->success('Partner profile is retrived successfully', $partner);
    }

    public function sendOTPByEmail(PartnerForgetPasswordRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $partner = Partner::where('email', $payload['email'])->first();

            if ($partner === null) {
                return $this->badRequest('Email does not exist');
            }

            $otp = rand(100000, 999999);

            $partner->update([
                'email_verify_code' => $otp,
                'email_expired_at' => Carbon::now()->addMinutes(5),
            ]);

            DB::commit();

            return $this->success('OTP is sent to your email', [
                'otp_code' => $otp,
                'email' => $payload['email'],
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return $this->internalServerError();
        }
    }

    public function verifyOTPByEmail(PartnerVerifiedOtp $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $partner = Partner::where('email', $payload['email'])->first();

            if ($partner !== null && $partner->email_verify_code !== $payload['otp']) {
                return $this->badRequest('Incorrect otp code');
            }

            if ($partner->email_expired_at < Carbon::now()) {
                return $this->badRequest('OTP expired');
            }

            $partner->update([
                'otp' => null,
                'email_verify_code' => Carbon::now(),
                'email_expired_at' => null,
            ]);

            DB::commit();

            return $this->success('OTP verified successfully. You can now reset your password.');

        } catch (Exception $e) {
            DB::rollBack();

            return $this->internalServerError();
        }
    }

    public function resetPassword(PartnerResetPassword $request)
    {
        DB::beginTransaction();

        try {
            $payload = collect($request->validated());

            $partner = Partner::where('email', $payload['email'])->first();

            if (! $partner) {
                return $this->badRequest('Email not found.');
            }

            $partner->update([
                'password' => Hash::make($payload['password']),
                'otp' => null,
            ]);

            DB::commit();

            return $this->success('Password reset successfully. You can now log in.', null);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function changePassword(PartnerChangePasswordRequest $request)
    {
        $partner = auth('partner')->user();
        $payload = collect($request->validated());

        if ($partner && $partner->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $check = Hash::check($payload['old_password'], $partner->password);

                if ($check === false) {
                    DB::commit();

                    return $this->badRequest('Old password does not match');
                }

                $partner->update([
                    'password' => $payload['password'],
                ]);

                DB::commit();

                return $this->success('Password is changed successfully', null);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        return $this->badRequest('Your account is not active');
    }

    public function updatePaymentPassword(PartnerPaymentPasswordUpdateRequest $request)
    {
        $partner = auth('partner')->user();
        $payload = collect($request->validated());

        if ($partner && $partner->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $partner->update($payload->toArray());
                DB::commit();

                return $this->success('Partner payment password is updated successfully', null);
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        return $this->badRequest('Your account is not active');
    }

    public function confirmPaymentPassword(ConfrimPaymentPasswordRequest $request)
    {
        $agent = auth('agent')->user();
        $payload = collect($request->validated());

        if ($agent->status === AgentStatusEnum::ACTIVE->value && $agent->payment_password !== null && $agent->kyc_status === KycStatusEnum::FULL_KYC->value) {

            try {
                $check = Hash::check($payload['payment_password'], $agent->payment_password);
                if ($check === false) {
                    return $this->badRequest('Payment password does not match');
                }

                $agent->update($payload->toArray());

                return $this->success('payment password is match', true);
            } catch (Exception $e) {
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now');
    }
}
