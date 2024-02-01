<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\AdminStatusEnum;
use App\Enums\GeneralStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\PointLabelEnum;
use App\Enums\UserStatusEnum;
use App\Enums\MemberDiscountStatus;
use App\Helpers\Enum;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    /**
     * APIs for retrive status record [support multiple status type]
     *
     * @urlParam type string. Example: user,business_type,employee,item,general,purchase
     */
    public function index(Request $request)
    {
        $requestStatus = explode(',', $request->get('type'));

        $allowableStatus = [
            'user' => (new Enum(UserStatusEnum::class))->values(),
            'admin' => (new Enum(AdminStatusEnum::class))->values(),
            'general' => (new Enum(GeneralStatusEnum::class))->values(),
            'order' => (new Enum(OrderStatusEnum::class))->values(),
            'payment_type' => (new Enum(PaymentTypeEnum::class))->values(),
            'point' => (new Enum(PointLabelEnum::class))->values(),
            'member' => (new Enum(MemberDiscountStatus::class))->values()
        ];

        $statusTypes = collect($allowableStatus)->filter(function ($value, $index) use ($requestStatus) {
            return in_array($index, $requestStatus);
        });

        return $this->success('Status type list are successfully retrived', $statusTypes);
    }
}
