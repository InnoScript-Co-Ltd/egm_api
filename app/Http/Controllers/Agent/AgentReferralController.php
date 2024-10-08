<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Enums\AgentTypeEnum;
use App\Enums\KycStatusEnum;
use App\Enums\ReferralTypeEnm;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AgentReferralStoreRequest;
use App\Models\Agent;
use App\Models\Referral;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AgentReferralController extends Controller
{
    public function check($referral)
    {
        DB::beginTransaction();
        try {

            $referralLink = Referral::where(['link' => $referral])->first();

            if ($referralLink === null) {
                DB::commit();

                return $this->badRequest('Referral link does not found');
            }

            $checkExpired = Carbon::parse($referralLink->expired_at)->isPast();

            if ($checkExpired === true) {
                DB::commit();

                return $this->badRequest('Referral link is expired');
            }

            DB::commit();

            return $this->success('Referral link is aviliable', $referralLink);

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function storeCommissionReferral(AgentReferralStoreRequest $request)
    {
        $auth = auth('agent')->user();

        DB::beginTransaction();
        $payload = $request->validated();

        try {

            $agent = Agent::with(['deposits'])->findOrFail($auth->id)->toArray();

            if ($payload['commission'] > $agent['commission'] || $payload['commission'] < 5) {
                return $this->validationError('commission percentage does not match', [
                    'commission' => ['invalid commission percentage'],
                ]);
            }

            if ($agent['kyc_status'] === KycStatusEnum::FULL_KYC->value && $agent['status'] === AgentStatusEnum::ACTIVE->value) {

                $linkArray = explode('-', Str::uuid());
                $link = implode('', $linkArray);

                $referral = Referral::create([
                    'main_agent_id' => $agent['agent_type'] === AgentTypeEnum::MAIN_AGENT->value ? $agent['id'] : null,
                    'agent_id' => $agent['agent_type'] === AgentTypeEnum::SUB_AGENT->value ? $agent['id'] : null,
                    'partner_id' => null,
                    'agent_type' => $agent['agent_type'],
                    'expired_at' => Carbon::now()->addMonths(1),
                    'link' => strtoupper($link),
                    'count' => 0,
                    'commission' => $payload['commission'],
                    'referral_type' => ReferralTypeEnm::COMMISSION_REFERRAL->value,
                ]);

                DB::commit();

                return $this->success('Reference link is generated successfully', $referral);
            }

            DB::commit();

            return $this->badRequest('Reference link is generated failed');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function index()
    {
        $agent = auth('agent')->user();

        if ($agent && $agent->status === 'ACTIVE') {

            DB::beginTransaction();

            $agentType = $agent->agent_type === AgentTypeEnum::MAIN_AGENT->value ? [
                'main_agent_id' => $agent->id,
            ] : [
                'agent_id' => $agent->id,
            ];

            try {
                $referrals = Referral::where($agentType)
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();

                return $this->success('referral link list is successfully retrived', $referrals);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
