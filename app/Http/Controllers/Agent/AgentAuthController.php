<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Enums\KycStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\AgentAuthLoginRequest;
use App\Http\Requests\Agents\AgentChangePasswordRequest;
use App\Http\Requests\Agents\AgentPaymentPasswordUpdateRequest;
use App\Http\Requests\Agents\ConfrimPaymentPasswordRequest;
use App\Models\Agent;
use App\Models\AgentBankAccount;
use App\Models\Deposit;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AgentAuthController extends Controller
{
    protected $selected = [
        'id',
        'address',
        'agent_type',
        'commission',
        'dob',
        'email',
        'first_name',
        'last_name',
        'kyc_status',
        'nrc',
        'nrc_back',
        'nrc_front',
        'partner_id',
        'phone',
        'point',
        'referral_type',
        'profile',
        'status',
        'username',
    ];

    protected function createNewToken($token)
    {
        $id = auth('agent')->user()->id;

        $user = Agent::findOrFail($id);

        return $this->success('User is successfully signed in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }

    public function profile()
    {
        DB::beginTransaction();

        $id = auth('agent')->user()->id;

        try {
            $agent = Agent::select($this->selected)->findOrFail($id);

            if ($agent) {
                $deposits = Deposit::where(['agent_id' => $agent->id])
                    ->whereDate('expired_at', '>', Carbon::now()->toDateString())
                    ->count();

                $agent['allow_referral'] = false;
                $agent['allow_deposit'] = false;

                if ($deposits > 0 && $agent->kyc_status === KycStatusEnum::FULL_KYC->value && $agent->status === AgentStatusEnum::ACTIVE->value) {
                    $agent['allow_referral'] = true;
                }

                $bankAccounts = AgentBankAccount::where(['agent_id' => $agent->id])->count();

                if ($bankAccounts > 0 && $agent->kyc_status === KycStatusEnum::FULL_KYC->value && $agent->status === AgentStatusEnum::ACTIVE->value) {
                    $agent['allow_deposit'] = true;
                }

                return $this->success('Agent profile is successfully retrived', $agent);
            }

            return $this->unauthenticated('Unauthorized');
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login(AgentAuthLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = Agent::where(['email' => $payload['email']])->first();

            if (! $user) {
                return $this->badRequest('Account does not found');
            }

            if ($user->status !== AgentStatusEnum::ACTIVE->value) {
                return $this->badRequest('Account is not active');
            }

            $token = auth()->guard('agent')->attempt($payload->toArray());

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
