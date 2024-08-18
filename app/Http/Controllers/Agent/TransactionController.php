<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Dashboard\Controller;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $agent = auth('agent')->user();

        if ($agent && $agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {

            DB::beginTransaction();

            try {
                $transactions = Transaction::where(['agent_id' => $agent->id])
                    ->searchQuery()
                    ->sortingQuery()
                    ->filterQuery()
                    ->filterDateQuery()
                    ->paginationQuery();
                DB::commit();

                return $this->success('Agent transactions are retrived successfully', $transactions);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
        DB::commit();

        return $this->badRequest('You does not have permission right now');
    }

    public function show($id)
    {
        $agent = auth('agent')->user();

        if ($agent && $agent->kyc_status === 'FULL_KYC' && $agent->status === 'ACTIVE') {

            DB::beginTransaction();

            try {
                $transaction = Transaction::where(['id' => $id])->first();
                DB::commit();

                return $this->success('Agent transaction is retrived successfully', $transaction);

            } catch (Exception $e) {
                DB::rollback();
                throw $e;
            }
        }
        DB::commit();

        return $this->badRequest('You does not have permission right now');
    }
}
