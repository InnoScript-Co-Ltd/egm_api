<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\AdminStatusEnum;
use App\Enums\AgentStatusEnum;
use App\Enums\AppTypeEnum;
use App\Enums\GeneralStatusEnum;
use App\Enums\KycStatusEnum;
use App\Enums\MemberCardStatusEnum;
use App\Enums\MemberDiscountStatus;
use App\Enums\MembershipOrderStatusEnum;
use App\Enums\MemberStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\PointLabelEnum;
use App\Enums\UserStatusEnum;
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
            'member' => (new Enum(MemberStatusEnum::class))->values(),
            'member_discount' => (new Enum(MemberDiscountStatus::class))->values(),
            'membercard' => (new Enum(MemberCardStatusEnum::class))->values(),
            'memberOrder' => (new Enum(MembershipOrderStatusEnum::class))->values(),
            'apptype' => (new Enum(AppTypeEnum::class))->values(),
            'agent' => (new Enum(AgentStatusEnum::class))->values(),
            'kyc' => (new Enum(KycStatusEnum::class))->values()
        ];

        $statusTypes = collect($allowableStatus)->filter(function ($value, $index) use ($requestStatus) {
            return in_array($index, $requestStatus);
        });

        return $this->success('Status type list are successfully retrived', $statusTypes);
    }
}
