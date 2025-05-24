<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\RepaymentStatusEnum;
use App\Enums\TransactionStatusEnum;
use App\Helpers\Snowflake;
use App\Http\Requests\Dashboard\DashboardTransactionUpdateRequest;
use App\Models\Partner;
use App\Models\Repayment;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardTransactionController extends Controller
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
        try {
            $transactions = Transaction::where(['sender_type' => 'PARTNER'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Transactions are retrived successfully', $transactions);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function indexByPartner($id)
    {
        try {
            $transactions = Transaction::where([
                'sender_type' => 'PARTNER',
                'sender_id' => $id,
            ])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Transactions are retrived successfully by partner', $transactions);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $transaction = Transaction::with(['repayments'])
                ->findOrFail($id);

            return $this->success('Transaction is retrived successfully', $transaction);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function update(DashboardTransactionUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());

        if (isset($payload['transaction_screenshoot'])) {
            $ImagePath = $payload['transaction_screenshoot']->store('images', 'public');
            $image = explode('/', $ImagePath)[1];
            $payload['transaction_screenshoot'] = $image;
        }

        DB::beginTransaction();

        try {
            $transaction = Transaction::with(['repayments'])
                ->findOrFail($id);
            $transaction->update($payload->toArray());

            DB::commit();

            return $this->success('Transaction is updated successfully', $transaction);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function makePayment($id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);

            if ($transaction->status !== TransactionStatusEnum::DEPOSIT_PENDING->value) {
                return $this->badRequest('Transaction is not pending');
            }

            $partner = Partner::findOrFail($transaction->sender_id);

            if ($partner->kyc_status !== 'FULL_KYC' || $partner->status !== 'ACTIVE') {
                return $this->badRequest('Partner is not active or not full kyc');
            }

            $transactionUpdate['status'] = TransactionStatusEnum::DEPOSIT_PAYMENT_ACCEPTED->value;
            $transactionUpdate['expired_at'] = Carbon::parse($transaction->created_at)->addMonths(6);
            $transactionUpdate['updated_at'] = Carbon::now();

            $created_at = Carbon::parse($transaction->created_at);
            $expired_at = Carbon::parse($transactionUpdate['expired_at']);

            $months = [];

            while ($created_at->lte($expired_at)) {
                $months[] = $created_at->format('Y-m-d');
                $created_at->addMonth();
            }

            $repayments = collect($months)->map(function ($month) use ($transaction, $transactionUpdate) {

                $closeDays = 25;
                $daysInMonth = Carbon::parse($month)->daysInMonth;
                $previousLeftDays = $daysInMonth - $closeDays;

                $repaymentPayload['id'] = (new Snowflake)->short();
                $repaymentPayload['transaction_id'] = $transaction->id;
                $repaymentPayload['partner_id'] = $transaction->sender_id;
                $repaymentPayload['total_amount'] = $transaction->package_deposit_amount * $transaction->package_roi_rate / 100;
                $repaymentPayload['oneday_amount'] = $repaymentPayload['total_amount'] / 30;
                $repaymentPayload['created_at'] = $transaction->created_at;
                $repaymentPayload['status'] = RepaymentStatusEnum::AVAILABLE_WITHDRAW->value;
                $repaymentPayload['date'] = Carbon::parse($month)->format('Y-m').'-'.'26';
                $repaymentPayload['total_days'] = 30;
                $repaymentPayload['count_days'] = $previousLeftDays + $closeDays;
                $repaymentPayload['amount'] = $repaymentPayload['oneday_amount'] * $repaymentPayload['count_days'];
                $repaymentPayload['created_at'] = Carbon::now();

                if (Carbon::parse($transaction->created_at)->format('Y-m-d') === $month) {
                    $dayInMonth = Carbon::parse($month)->day;
                    $repaymentPayload['count_days'] = ($closeDays - $dayInMonth) + 1;
                    $repaymentPayload['amount'] = $repaymentPayload['oneday_amount'] * $repaymentPayload['count_days'];
                    $previousLeftDays = $daysInMonth - $closeDays;
                }

                if (Carbon::parse($transactionUpdate['expired_at'])->format('Y-m-d') === $month) {
                    $dayInMonth = Carbon::parse($month)->day;
                    $repaymentPayload['count_days'] = $previousLeftDays + $closeDays;
                    $repaymentPayload['amount'] = $repaymentPayload['oneday_amount'] * $repaymentPayload['count_days'];
                }

                return $repaymentPayload;
            });

            Repayment::insert($repayments->toArray());
            $transaction->update(['status' => TransactionStatusEnum::DEPOSIT_PAYMENT_ACCEPTED->value]);
            DB::commit();

            return $this->success('Payment deposit is successfully', $repayments);
        } catch (Exception $e) {
            DB::rollback();

            return $e;

            return $this->internalServerError('Payment deposit is failed');
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
            throw ($e);

            return $this->internalServerError('Payment deposit is failed');
        }
    }
}
