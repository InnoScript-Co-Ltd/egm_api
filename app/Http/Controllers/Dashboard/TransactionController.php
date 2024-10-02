<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\TransactionStatusEnum;
use App\Models\Deposit;
use App\Models\Repayment;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function partnerIndex()
    {
        DB::beginTransaction();
        try {
            $transactions = Transaction::where(['sender_type' => 'PARTNER'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('Partner Ttransactions are retrived successfully', $transactions);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

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
            $payload['roi_amount'] = $transaction->package_deposit_amount * $transaction->package_roi_rate / 100;

            $deposit = Deposit::create($payload);

            $created_at = Carbon::parse($deposit->created_at);
            $expired_at = Carbon::parse($deposit->expired_at);

            $months = [];

            while ($created_at->lte($expired_at)) {
                $months[] = $created_at->format('Y-m');
                $created_at->addMonth();
            }

            $repaymentPayload['deposit_id'] = $deposit->id;
            $repaymentPayload['transaction_id'] = $transaction->id;

            collect($months)->map(function ($month) use ($repaymentPayload, $deposit) {

                $depositYearMonth = Carbon::now()->year.'-'.Carbon::now()->month;
                $oneDayROI = $deposit->roi_amount / 30;

                $repaymentPayload['total_amount'] = $deposit->roi_amount;
                $repaymentPayload['oneday_amount'] = $deposit->roi_amount / 30;
                $repaymentPayload['total_days'] = 30;

                if ($depositYearMonth === $month) {
                    $repaymentPayload['date'] = $month.'-25';
                    $repaymentDays = Carbon::parse($deposit->created_at)->diffInDays($repaymentPayload['date']);
                    $repaymentPayload['amount'] = $oneDayROI * $repaymentDays;
                    $repaymentPayload['count_days'] = $repaymentDays;
                } else {
                    $repaymentPayload['date'] = $month.'-25';
                    $previousMonth = Carbon::parse($repaymentPayload['date'])->addMonths(-1);
                    $repaymentDays = Carbon::parse($previousMonth)->diffInDays($repaymentPayload['date']);
                    $repaymentPayload['amount'] = $oneDayROI * $repaymentDays;
                    $repaymentPayload['count_days'] = $repaymentDays;
                }

                Repayment::create($repaymentPayload);
            });

            $transaction->update(['status' => TransactionStatusEnum::DEPOSIT_PAYMENT_ACCEPTED->value]);

            DB::commit();

            return $this->success('Payment deposit is successfully', $payload);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function makeReject($id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->update(['status' => TransactionStatusEnum::DEPOSIT_REJECT->value]);
            DB::commit();

            return $this->success('Payment deposit is reject by admin', true);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
