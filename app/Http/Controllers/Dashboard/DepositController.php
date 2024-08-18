<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\TransactionStatusEnum;
use App\Http\Requests\Dashboard\MakePaymentRequest;
use App\Models\Deposit;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function index()
    {
        DB::beginTransaction();
        try {

            $deposits = Deposit::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Deposits are retrived successfully', $deposits);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {
            $deposit = Deposit::findOrFail($id);
            DB::commit();

            return $this->success('Deposit is retrived successfully', $deposit);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(MakePaymentRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($payload['transaction_id']);

            if ($transaction->status === TransactionStatusEnum::DEPOSIT_PENDING->value) {
                $payload['expired_at'] = Carbon::now()->addMonths(6);
                $payload['deposit_amount'] = $transaction->package_deposit_amount;
                $payload['roi_amount'] = $transaction->package_deposit_amount * $transaction->package_roi_rate / 100;
                $payload['commission_amount'] = $transaction->package_deposit_amount * 1 / 100;
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
