<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\MemberOrder;
use Illuminate\Support\Facades\DB;

class MembershipOrderController extends Controller
{
    public function index()
    {
        DB::beginTransaction();

        try {
            $orders = MemberOrder::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            return $this->success('Member order list is successfully retrived', $orders);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {
            $order = MemberOrder::findOrFail($id);
            DB::commit();

            return $this->success('Member Order detail is successfully retrived', $order);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
