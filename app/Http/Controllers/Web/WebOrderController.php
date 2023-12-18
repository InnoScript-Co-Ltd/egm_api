<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Requests\OrderUpdateRequest;
use App\Models\DeliveryAddress;
use App\Models\Order;
use App\Models\User;
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
