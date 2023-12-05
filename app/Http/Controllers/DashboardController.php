<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;
use App\Enums\OrderStatusEnum;
use App\Enums\GeneralStatusEnum;

class DashboardController extends Controller
{
    public function orderCount()
    {
        $isPendingStatus = count(collect(Order::where(['status' => OrderStatusEnum::PENDING->value])->get())->toArray());
        $isVerifiedStatus = count(collect(Order::where(['status' => OrderStatusEnum::VERIFIED->value])->get())->toArray());
        $isDeliveryStatus = count(collect(Order::where(['status' => OrderStatusEnum::DELIVERY->value])->get())->toArray());
        $isCompleteStatus = count(collect(Order::where(['status' => OrderStatusEnum::COMPLETE->value])->get())->toArray());

        $orderCount = count(collect(Order::all())->toArray());
        return response()->json([
            "total" => $orderCount,
            "pending" => $isPendingStatus,
            "verified" => $isVerifiedStatus,
            "delivery" => $isDeliveryStatus,
            "complete" => $isCompleteStatus
        ]);
    }

    public function itemCount()
    {
        $isActiveStatus = count(collect(Item::where(['status' => GeneralStatusEnum::ACTIVE->value])->get())->toArray());
        $isDisableStatus = count(collect(Item::where(['status' => GeneralStatusEnum::DISABLE->value])->get())->toArray());
        $isDeleteStatus = count(collect(Item::where(['status' => GeneralStatusEnum::DELETED->value])->get())->toArray());

        $itemCount = count(collect(Item::all())->toArray());
        return response()->json([
            "total" => $itemCount,
            "active" => $isActiveStatus,
            "disable" => $isDisableStatus,
            "delete" => $isDeleteStatus
        ]);
    }
}
