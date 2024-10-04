<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Repayment;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentRepaymentController extends Controller
{
    public function indexThisMonth($month)
    {
        $agent = auth('agent')->user();

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {

            DB::beginTransaction();

            try {
                $repayments = Repayment::where(['agent_id' => $agent->id])
                    ->whereMonth('date', $month)
                    ->get();
                DB::commit();

                return $this->success('this months repayments are retrived successfully', $repayments);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

    }

    public function index($id)
    {
        $agent = auth('agent')->user();

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {

            DB::beginTransaction();

            try {
                $repayments = Repayment::where(['agent_id' => $agent->id, 'deposit_id' => $id])
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();
                DB::commit();

                return $this->success('Deposit repayments are retrived successfully', $repayments);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

    }
}
