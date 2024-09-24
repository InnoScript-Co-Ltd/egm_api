<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\TransactionStatusEnum;
use App\Models\Deposit;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {
            $transactions = Transaction::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Transactions are retrived successfully', $transactions);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);
            DB::commit();

            return $this->success('Transaction is retrived successfully', $transaction);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function makePayment($id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);

            if ($transaction->sender_type === 'MAIN_AGENT' || $transaction->sender_type === 'SUB_AGENT') {
                $payload['agent_id'] = $transaction->sender_id;
            } else {
                $payload['partner_id'] = $transaction->sender_id;
            }

            $payload['status'] = TransactionStatusEnum::DEPOSIT_PAYMENT_ACCEPTED->value;
            $payload['expired_at'] = Carbon::now()->addMonths(6);
            $payload['deposit_amount'] = $transaction->package_deposit_amount;

            Deposit::create($payload);
            $transaction->update(['status' => TransactionStatusEnum::DEPOSIT_PAYMENT_ACCEPTED->value]);

            DB::commit();

            return $this->success('Payment deposit is successfully', $payload);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
