<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Enums\AgentTypeEnum;
use App\Enums\KycStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
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

    public function store()
    {
        $agent = auth('agent')->user();
        DB::beginTransaction();
        try {
            if ($agent->status === AgentStatusEnum::ACTIVE->value && $agent->kyc_status === KycStatusEnum::FULL_KYC->value) {

                $linkArray = explode('-', Str::uuid());
                $link = implode('', $linkArray);

                if ($agent->agent_type === AgentTypeEnum::MAIN_AGENT->value) {
                    $payload['main_agent_id'] = $agent->id;
                    $payload['reference_id'] = $agent->id;
                    $payload['partner_id'] = $agent->partner_id;
                    $payload['agent_id'] = $agent->id;
                    $payload['agent_type'] = $agent->agent_type;
                    $payload['expired_at'] = Carbon::now()->addMonths(6);
                    $payload['link'] = strtoupper($link);
                    $payload['count'] = 0;

                    $referral = Referral::create($payload);
                    DB::commit();

                    return $this->success('Reference link is generated successfully', $referral);
                }

                $referral = Referral::create([
                    'main_agent_id' => $agent->main_agent_id,
                    'reference_id' => $agent->id,
                    'partner_id' => $agent->partner_id,
                    'agent_id' => $agent->id,
                    'agent_type' => $agent->agent_type,
                    'expired_at' => Carbon::now()->addMonths(6),
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

    public function index()
    {
        $agent = auth('agent')->user();

        if ($agent && $agent->status === 'ACTIVE') {

            DB::beginTransaction();

            try {
                $agentPackage = Referral::where(['agent_id' => $agent->id])
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();

                return $this->success('agent package list is successfully retrived', $agentPackage);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
