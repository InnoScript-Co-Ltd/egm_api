<?php

namespace App\Http\Controllers\Partner;

use App\Enums\AgentStatusEnum;
use App\Enums\KycStatusEnum;
use App\Enums\PartnerStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AgentChangePasswordRequest;
use App\Http\Requests\Agents\AgentPaymentPasswordUpdateRequest;
use App\Http\Requests\Agents\ConfrimPaymentPasswordRequest;
use App\Http\Requests\Partner\PartnerLoginRequest;
use App\Models\Partner;
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

    public function changePassword(AgentChangePasswordRequest $request)
    {
        $agent = auth('agent')->user();
        $payload = collect($request->validated());

        if ($agent && $agent->status === 'ACTIVE' && $agent->kyc_status === 'FULL_KYC') {
            DB::beginTransaction();

            try {
                $check = Hash::check($payload['old_password'], $agent->password);

                if ($check === false) {
                    DB::commit();

                    return $this->badRequest('Old password does not match');
                }

                $agent->update([
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

    public function updatePaymentPassword(AgentPaymentPasswordUpdateRequest $request)
    {
        $agent = auth('agent')->user();
        $payload = collect($request->validated());

        if ($agent && $agent->status === 'ACTIVE' && $agent->kyc_status === 'FULL_KYC') {
            DB::beginTransaction();

            try {
                $agent->update($payload->toArray());
                DB::commit();

                return $this->success('Agent payment password is updated successfully', null);

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
