<?php

namespace App\Http\Controllers\Merchant;

use App\Models\MemberOrder;

class DashboardController extends Controller
{
    public function count($date)
    {

        $orderCount = MemberOrder::whereDate('created_at', $date);
        $orderTotalAmount = $orderCount->get()->sum('amount');
        $orderTotalPayAmount = $orderCount->get()->sum('pay_amount');
        $orderTotalDiscount = $orderCount->get()->sum('discount');

        $count = [
            'order' => $orderCount->count(),
            'pay_amount' => $orderTotalPayAmount,
            'amount' => $orderTotalAmount,
            'discount' => $orderTotalDiscount,
        ];

        return $this->success('Order count is successfully retrived', $count);
    }
}
