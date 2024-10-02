<?php

namespace App\Http\Controllers\Agent;

use App\Enums\RepaymentStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Models\Deposit;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AgentDashboardController extends Controller
{
    public function index()
    {
        $agent = auth('agent')->user();

        if ($agent && $agent->status === 'ACTIVE' && $agent->kyc_status === 'FULL_KYC') {

            DB::beginTransaction();

            try {

                $deposits = Deposit::with(['repayments'])
                    ->select(['id', 'agent_id', 'deposit_amount', 'roi_amount', 'commission_amount', 'expired_at', 'created_at'])
                    ->where(['agent_id' => $agent->id])
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

                DB::commit();

                return $this->success('agent package list is successfully retrived', [
                    'total_deposit_amount' => $totalDeposit,
                    'total_repayment' => $totalRepayment,
                ]);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
