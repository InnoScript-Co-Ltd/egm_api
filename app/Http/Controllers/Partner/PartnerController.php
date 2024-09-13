<?php

namespace App\Http\Controllers\Partner;

use App\Enums\KycStatusEnum;
use App\Enums\PartnerStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Partner\GenerateReferenceLinkRequest;
use App\Http\Requests\Partner\PartnerAccountUpdateRequest;
use App\Http\Requests\Partner\PartnerInfoUpdateRequest;
use App\Http\Requests\Partner\PartnerKYCUpdateRequest;
use App\Models\Partner;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    public function referenceLink(GenerateReferenceLinkRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {

            $partner = Partner::findOrFail($payload['partner_id']);

            if ($partner->kyc_status === KycStatusEnum::FULL_KYC->value && $partner->status === PartnerStatusEnum::ACTIVE->value) {
                $referenceLink['partner_id'] = $partner->id;
                $referenceLink['expired_at'] = Carbon::now()->addMonths(6);
                $token = Crypt::encrypt(json_encode($referenceLink));

                DB::commit();

                return $this->success('Reference link is generated successfully', $token);
            }

            DB::commit();

            return $this->badRequest('Reference link is generated failed');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function updateInfo(PartnerInfoUpdateRequest $request)
    {
        $partner = auth('partner')->user();
        $payload = collect($request->validated());

        if ($partner->status === PartnerStatusEnum::ACTIVE->value) {
            $partner->update($payload->toArray());
            DB::commit();

            return $this->success('Partner info is updated successfully', $partner);
        }

        return $this->badRequest('Your account is not active');
    }

    public function updateAccount(PartnerAccountUpdateRequest $request)
    {
        $partner = auth('partner')->user();
        $payload = collect($request->validated());

        if ($partner->status === PartnerStatusEnum::ACTIVE->value) {
            $partner->update($payload->toArray());
            DB::commit();

            return $this->success('Partner account is updated successfully', $partner);
        }

        return $this->badRequest('Your account is not active');
    }

    public function updateKYC(PartnerKYCUpdateRequest $request)
    {
        $partner = auth('partner')->user();
        $payload = collect($request->validated());

        if ($partner->status === PartnerStatusEnum::ACTIVE->value && $partner->kyc_status === KycStatusEnum::CHECKING->value) {

            if (isset($payload['nrc_front'])) {
                $nrcFrontImagePath = $payload['nrc_front']->store('images', 'public');
                $nrcFrontImage = explode('/', $nrcFrontImagePath)[1];
                $payload['nrc_front'] = $nrcFrontImage;
            }

            if (isset($payload['nrc_back'])) {
                $nrcBackImagePath = $payload['nrc_back']->store('images', 'public');
                $nrcBackImage = explode('/', $nrcBackImagePath)[1];
                $payload['nrc_back'] = $nrcBackImage;
            }

            $partner->update($payload->toArray());
            DB::commit();

            return $this->success('Partner kyc info is updated successfully', $partner);
        }

        return $this->badRequest('Your account is not active');
    }
}
