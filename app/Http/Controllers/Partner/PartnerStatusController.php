<?php

namespace App\Http\Controllers\Partner;

use App\Enums\GeneralStatusEnum;
use App\Enums\KycStatusEnum;
use App\Enums\PackageTypeEnum;
use App\Enums\PartnerStatusEnum;
use App\Enums\PaymentTypeEnum;
use App\Enums\ReferralTypeEnm;
use App\Helpers\Enum;
use App\Http\Controllers\Dashboard\Controller;
use Illuminate\Http\Request;

class PartnerStatusController extends Controller
{
    public function index(Request $request)
    {
        $partner = auth('partner')->user();
        $requestStatus = explode(',', $request->get('type'));

        if ($partner->kyc_status === 'FULL_KYC' && $partner->status === 'ACTIVE') {
            $allowableStatus = [
                'kyc' => (new Enum(KycStatusEnum::class))->values(),
                'general' => (new Enum(GeneralStatusEnum::class))->values(),
                'payment_type' => (new Enum(PaymentTypeEnum::class))->values(),
                'partner' => (new Enum(PartnerStatusEnum::class))->values(),
                'package' => (new Enum(PackageTypeEnum::class))->values(),
                'referral_type' => (new Enum(ReferralTypeEnm::class))->values(),
            ];

            $statusTypes = collect($allowableStatus)->filter(function ($value, $index) use ($requestStatus) {
                return in_array($index, $requestStatus);
            });

            return $this->success('Status type list are successfully retrived', $statusTypes);
        }

        return $this->badRequest('You does not have permission right now.');
    }
}
