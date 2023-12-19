<?php

namespace App\Http\Controllers\Web;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class WebOrderController extends WebController
{
    public function index()
    {
        DB::beginTransaction();
        try {
            $order = Order::with(['users', 'deliveryAddress'])
                ->searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('Order list is successfully retrived', $order);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
