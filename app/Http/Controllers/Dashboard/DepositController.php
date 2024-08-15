<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Deposit;
use Exception;
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
}
