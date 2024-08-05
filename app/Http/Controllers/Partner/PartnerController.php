<?php

namespace App\Http\Controllers\Partner;

use App\Enums\KycStatusEnum;
use App\Enums\PartnerStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Partner\GenerateReferenceLinkRequest;
use App\Http\Requests\Partner\PartnerStoreRequest;
use App\Mail\EmailVerifyCode;
use App\Models\Partner;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Mail;

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
            dd($e);
            DB::rollBack();
            throw $e;
        }

    }

    public function store(PartnerStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            if (isset($payload['profile'])) {
                $profileImagePath = $payload['profile']->store('images', 'public');
                $profileImage = explode('/', $profileImagePath)[1];
                $payload['profile'] = $profileImage;
            }

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

            $payload['email_verify_code'] = rand(100000, 999999);
            $payload['email_expired_at'] = Carbon::now()->addMinutes(5);
            // Mail::to($payload['email'])->send(new EmailVerifyCode($payload['email_verify_code']));

            $partner = Partner::create($payload->toArray());
            DB::commit();

            return $this->success('Partner account is successfully created', $partner);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
