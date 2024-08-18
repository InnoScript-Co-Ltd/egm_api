<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\TransactionStatusEnum;
use App\Http\Requests\Dashboard\MakePaymentRequest;
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

    public function makePayment(MakePaymentRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($payload['transaction_id']);

            if ($payload['status'] === TransactionStatusEnum::DEPOSIT_PENDING->value) {
                $payload['expired_at'] = Carbon::now()->addMonths(6);
                $payload['deposit_amount'] = $transaction->package_deposit_amount;
                $payload['agent_id'] = $transaction->agent_id;

                Deposit::create($payload->toArray());

                $transaction->update(['status' => TransactionStatusEnum::DEPOSIT_PAYMENT_ACCEPTED->value]);
                DB::commit();

                return $this->success('Payment deposit is successfully', null);
            }
            DB::commit();

            return $this->badRequest('Payment deposit process is failed, Please try again');
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
