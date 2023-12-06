<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Item;
use App\Models\User;
use App\Enums\OrderStatusEnum;
use App\Enums\GeneralStatusEnum;
use App\Enums\UserStatusEnum;
use App\Helpers\Enum;

class DashboardController extends Controller
{
    public function countByStatus($modelClass, $enumClass, $statusColumn)
    {
        $statusEnums = (new Enum($enumClass))->values();
        $response = ["total" => $modelClass::count()];

        foreach ($statusEnums as $status) {
            $response[strtolower($status)] = $modelClass::where($statusColumn, $status)->count();
        }

        return $response;
    }

    public function orderCount()
    {
        return $this->countByStatus(Order::class, OrderStatusEnum::class, 'status');
    }

    public function itemCount()
    {
        return $this->countByStatus(Item::class, GeneralStatusEnum::class, 'status');
    }

    public function userCount()
    {
        return $this->countByStatus(User::class, UserStatusEnum::class, 'status');
    }

    public function count()
    {
        $item = $this->itemCount();
        $order = $this->orderCount();
        $user = $this->userCount();

        $response = [
            "item" => $item,
            "order" => $order,
            "user" => $user
        ];

        return $this->success('Count list is successfully retrieved', $response);
    }
}
