<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Deposit;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentDepositController extends Controller
{
    public function index()
    {
        $agent = auth('agent')->user();

        if ($agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {

            DB::beginTransaction();

            try {
                $deposits = Deposit::select(['id', 'agent_id', 'deposit_amount', 'roi_amount', 'created_at', 'expired_at'])
                    ->where(['agent_id' => $agent->id])
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();
                DB::commit();

                return $this->success('Agent deposit transactions are retrived successfully', $deposits);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }

    }
}
