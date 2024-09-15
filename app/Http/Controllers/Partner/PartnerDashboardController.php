<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Deposit;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class PartnerDashboardController extends Controller
{
    public function index()
    {
        $partner = auth('partner')->user();
        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {

            DB::beginTransaction();

            try {
                $total_deposit_amount = Deposit::where(['partner_id' => $partner->id])
                    ->whereDate('expired_at', '>', Carbon::now()->toDateString())
                    ->sum('deposit_amount');

                $total_roi_amount = Deposit::where(['partner_id' => $partner->id])
                    ->whereDate('expired_at', '>', Carbon::now()->toDateString())
                    ->sum('roi_amount');

                DB::commit();

                return $this->success('partner static is created successfully', [
                    'total_deposit_amount' => $total_deposit_amount,
                    'total_roi_amount' => $total_roi_amount,
                ]);
            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }

        }

        return $this->badRequest('You does not have permission right now.');
    }
}
