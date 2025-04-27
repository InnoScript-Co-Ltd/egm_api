<?php

namespace App\Http\Controllers\Partner;

use App\Enums\KycStatusEnum;
use App\Enums\PartnerStatusEnum;
use App\Enums\ReferralTypeEnm;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Partner\PartnerReferralStoreRequest;
use App\Models\Partner;
use App\Models\Referral;
use App\Models\Transaction;
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

    public function show($id)
    {
        $partner = auth('partner')->user();

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            DB::beginTransaction();

            try {
                $referral = Referral::where(['id' => $id, 'partner_id' => $partner->id])->firstOrFail();
                $partners = Partner::select(['id', 'first_name', 'last_name', 'kyc_status', 'status', 'created_at', 'roi'])
                    ->wherein('id', $referral->register_agents)->get();

                $partners = collect($partners)->map(function ($value) use ($referral, $partner) {

                    $value['comission'] = $partner->roi - $referral->commission;

                    $transactionAmount = Transaction::select(['package_deposit_amount', 'sender_id', 'transaction_type', 'sender_type', 'status'])
                        ->where([
                            'sender_id' => $value->id,
                            'transaction_type' => 'DEPOSIT',
                            'sender_type' => 'PARTNER',
                            'status' => 'DEPOSIT_PAYMENT_ACCEPTED',
                        ])
                        ->sum('package_deposit_amount');

                    // $value['transaction_amount'] = $transactionAmount;
                    $value['comission_amount'] = $transactionAmount * $value['comission'] / 100;

                    return $value;
                });

                $referral->register_agents = $partners;
                $referral->register_agents_count = count($referral->register_agents);
                DB::commit();

                return $this->success('Partner referral links are successfully retrived', $referral);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

        return $this->badRequest('You does not have permission right now.');
    }

    public function partnerIndex($id)
    {
        $partner = auth('partner')->user();

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {

            try {
                $partners = Referral::where(['partner_id' => $partner->id])->get();
                $referralPartners = collect($partners)->map(function ($partner) {

                    if ($partner['register_agents'] !== null) {
                        $registerPartners = [];

                        collect($partner['register_agents'])->map(function ($value) use ($registerPartners) {
                            array_push($registerPartners, $value);
                        });

                        dd($registerPartners);
                    }

                });

                // $partners = Partner::with(['deposit'])
                //     ->whereIn('id', $referralPartners)->get();

                return $this->success('Partner referral links are successfully retrived', $referralPartners);
            } catch (Exception $e) {
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
