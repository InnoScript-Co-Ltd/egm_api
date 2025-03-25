<?php

namespace App\Http\Controllers\Partner;

use App\Enums\GeneralStatusEnum;
use App\Enums\RepaymentStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Models\BonusPoint;
use App\Models\Deposit;
use App\Models\Partner;
use App\Models\Referral;
use App\Models\Repayment;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PartnerDashboardController extends Controller
{
    public function index()
    {
        $partner = auth('partner')->user();

        if ($partner && $partner->status === 'ACTIVE' && $partner->kyc_status === 'FULL_KYC') {

            DB::beginTransaction();

            try {

                $bonusPoint = BonusPoint::select(['limit_amount'])
                    ->where(['status' => GeneralStatusEnum::ACTIVE->value])
                    ->first();

                $referrals = Referral::select(['link'])
                    ->where(['partner_id' => $partner->id])
                    ->where('count', '>', 0)
                    ->get();

                $referralCodes = collect($referrals)->map(function ($referral) {
                    return $referral['link'];
                });

                $partners = Partner::with(['deposit'])
                    ->whereIn('referral', $referralCodes)
                    ->get();

                $referralPartnerDeposit = collect($partners)->map(function ($partner) {
                    $totalDeposit = $partner['deposit']->sum('deposit_amount');
                    $commissionPercentage = 16 - $partner->roi;

                    return [
                        'referral_partner_deposit' => $totalDeposit,
                        'commission' => $totalDeposit * $commissionPercentage / 100,
                    ];
                });

                $deposits = Deposit::with(['repayments'])
                    ->select(['id', 'partner_id', 'deposit_amount', 'roi_amount', 'expired_at', 'created_at'])
                    ->where(['partner_id' => $partner->id])
                    ->whereDate('expired_at', '>', Carbon::now()->toDateString())
                    ->get();

                $totalDeposit = collect($deposits)->sum('deposit_amount');

                $totalRepayment = collect($deposits)->map(function ($deposit) {
                    $deposit['total_repayment'] = collect($deposit->repayments)->filter(function ($repayment) {
                        if ($repayment->status === RepaymentStatusEnum::AVAILABLE_WITHDRAW->value) {
                            return $repayment;
                        }
                    })->sum('amount');

                    return $deposit;
                })->sum('total_repayment');

                $month = Carbon::now()->format('m');

                $thisMonthRepayment = Repayment::where(['partner_id' => $partner->id])
                    ->whereMonth('date', $month)
                    ->sum('amount');

                DB::commit();

                return $this->success('deposit static is successfully retrived', [
                    'total_deposit_amount' => $totalDeposit,
                    'total_repayment' => $totalRepayment,
                    'this_month_repayment' => $thisMonthRepayment,
                    'commission_amount' => $referralPartnerDeposit->sum('commission'),
                    'bonus_point' => $bonusPoint['limit_amount'],
                    'progress_bonus_point' => $referralPartnerDeposit->sum('referral_partner_deposit'),
                ]);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
