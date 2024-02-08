<?php

namespace App\Http\Controllers\Merchant;

use App\Models\MemberOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function count () 
    {
        $today = Carbon::today();

        $orderCount = MemberOrder::whereDate('created_at', $today);
        $orderTotalAmount = $orderCount->get()->sum('amount');
        $orderTotalDiscount = $orderCount->get()->sum('discount');

        $count = [
            "order" => $orderCount->count(),
            "amount" => $orderTotalAmount,
            "discount" => $orderTotalDiscount
        ];

        return $this->success('Order count is successfully retrived', $count);
    }
}
