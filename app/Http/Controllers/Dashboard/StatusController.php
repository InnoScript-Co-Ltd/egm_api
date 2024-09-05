<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\AdminStatusEnum;
use App\Enums\AgentStatusEnum;
use App\Enums\AppTypeEnum;
use App\Enums\EmailContentTypeEnum;
use App\Enums\GeneralStatusEnum;
use App\Enums\KycStatusEnum;
use App\Enums\PackageTypeEnum;
use App\Enums\PartnerStatusEnum;
use App\Enums\PaymentTypeEnum;
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
            'payment_type' => (new Enum(PaymentTypeEnum::class))->values(),
            'apptype' => (new Enum(AppTypeEnum::class))->values(),
            'agent' => (new Enum(AgentStatusEnum::class))->values(),
            'kyc' => (new Enum(KycStatusEnum::class))->values(),
            'partner' => (new Enum(PartnerStatusEnum::class))->values(),
            'emailContent' => (new Enum(EmailContentTypeEnum::class))->values(),
            'package' => (new Enum(PackageTypeEnum::class))->values(),
        ];

        $statusTypes = collect($allowableStatus)->filter(function ($value, $index) use ($requestStatus) {
            return in_array($index, $requestStatus);
        });

        return $this->success('Status type list are successfully retrived', $statusTypes);
    }
}
