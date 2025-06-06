<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\HistoryTypeEnum;
use App\Models\History;

class DashboardHistoryController extends Controller
{
    public function indexTransaction()
    {
        try {
            $transactions = History::where(['type' => HistoryTypeEnum::DEPOSIT->value])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Transaction histories are retrieved successfully', $transactions);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function indexWithdraw()
    {
        try {
            $transactions = History::where(['type' => HistoryTypeEnum::WITHDRAW->value])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('withdraw histories are retrieved successfully', $transactions);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function indexRepayment()
    {
        try {
            $transactions = History::where(['type' => HistoryTypeEnum::REPAYMENT->value])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('repayment histories are retrieved successfully', $transactions);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function show($id)
    {
        try {
            $transaction = History::findOrFail($id);

            return $this->success('history record is retrieved successfully', $transaction);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
