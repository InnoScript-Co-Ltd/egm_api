<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Repayment;
use Exception;
use Illuminate\Support\Carbon;

class PartnerRepaymentController extends Controller
{
    public function thisMonthRepayment()
    {
        $partner = auth('partner')->user();
        $month = Carbon::now()->format('m');

        try {
            $repayments = Repayment::where([
                'partner_id' => $partner->id,
                'status' => 'AVAILABLE_WITHDRAW',
            ])
                ->whereMonth('date', $month)
                ->get();

            return $this->success('This month repayments retrieved successfully', [
                'repayments' => $repayments,
                'total_repayment' => $repayments->sum('amount'),
            ]);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
