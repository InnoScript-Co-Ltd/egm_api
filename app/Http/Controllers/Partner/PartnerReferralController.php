<?php

namespace App\Http\Controllers\Partner;

use App\Enums\KycStatusEnum;
use App\Enums\PartnerStatusEnum;
use App\Enums\ReferralTypeEnm;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Partner\PartnerReferralStoreRequest;
use App\Models\Partner;
use App\Models\Referral;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PartnerReferralController extends Controller
{
    public function index()
    {
        $partner = auth('partner')->user();

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $referrals = Referral::where(['partner_id' => $partner->id, 'agent_type' => 'PARTNER'])
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();

                DB::commit();

                return $this->success('Partner referral links are successfully retrived', $referrals);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }

    public function levelFourReferralStore()
    {
        $auth = auth('partner')->user();

        DB::beginTransaction();

        try {

            $partner = Partner::with(['deposit'])->findOrFail($auth->id)->toArray();

            if (count($partner['deposit']) > 0 && $partner['kyc_status'] === KycStatusEnum::FULL_KYC->value && $partner['status'] === PartnerStatusEnum::ACTIVE->value) {

                $linkArray = explode('-', Str::uuid());
                $link = implode('', $linkArray);

                $referral = Referral::create([
                    'partner_id' => $partner['id'],
                    'agent_type' => 'PARTNER',
                    'expired_at' => Carbon::now()->addMonths(1),
                    'link' => strtoupper($link),
                    'count' => 0,
                    'commission' => 0,
                    'referral_type' => ReferralTypeEnm::LEVEL_FOUR_REFERRAL->value,
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

    public function commissionReferralStore(PartnerReferralStoreRequest $request)
    {
        $auth = auth('partner')->user();
        $payload = collect($request->validated());

        if ($payload['percentage'] > $auth->roi) {
            return $this->badRequest('does not allow your percentage amount');
        }

        DB::beginTransaction();

        try {

            $partner = Partner::with(['deposit'])->findOrFail($auth->id)->toArray();

            if (count($partner['deposit']) > 0 && $partner['kyc_status'] === KycStatusEnum::FULL_KYC->value && $partner['status'] === PartnerStatusEnum::ACTIVE->value) {

                $linkArray = explode('-', Str::uuid());
                $link = implode('', $linkArray);

                $referral = Referral::create([
                    'partner_id' => $partner['id'],
                    'agent_type' => 'PARTNER',
                    'expired_at' => Carbon::now()->addMonths(12),
                    'link' => strtoupper($link),
                    'count' => 0,
                    'commission' => $payload['percentage'],
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
}
