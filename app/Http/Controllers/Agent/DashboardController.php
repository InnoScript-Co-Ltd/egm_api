<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Deposit;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $agent = auth('agent')->user();

        if ($agent && $agent->status === 'ACTIVE' && $agent->kyc_status === 'FULL_KYC') {

            DB::beginTransaction();

            try {

                $depositAmount = Deposit::where(['agent_id' => $agent->id])
                    ->whereDate('expired_at', '>', Carbon::now()->toDateString())
                    ->sum('deposit_amount');

                $roiAmount = Deposit::where(['agent_id' => $agent->id])
                    ->whereDate('expired_at', '>', Carbon::now()->toDateString())
                    ->sum('roi_amount');

                $commissionAmount = Deposit::where(['agent_id' => $agent->id])
                    ->whereDate('expired_at', '>', Carbon::now()->toDateString())
                    ->sum('commission_amount');

                DB::commit();

                return $this->success('agent package list is successfully retrived', [
                    'deposit_amount' => $depositAmount,
                    'roi_amount' => $roiAmount,
                    'commission_amount' => $commissionAmount,
                ]);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
    }
}
