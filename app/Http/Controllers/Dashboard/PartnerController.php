<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\EmailContentTypeEnum;
use App\Enums\GeneralStatusEnum;
use App\Enums\KycStatusEnum;
use App\Http\Requests\Dashboard\PartnerStoreRequest;
use App\Http\Requests\Dashboard\PartnerUpdateRequest;
use App\Mail\Dashboard\PartnerAccountEmailTemplate;
use App\Models\EmailContent;
use App\Models\Partner;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Mail;

class PartnerController extends Controller
{
    public function generatePassword()
    {
        try {
            $password = Str::password(16, true, true, false, false);
            DB::commit();

            return $this->success('Partner password is generated successfully', $password);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function index()
    {
        DB::beginTransaction();
        try {

            $partner = Partner::searchQuery()
                ->sortingQuery()
                ->filterQuery()
                ->filterDateQuery()
                ->paginationQuery();
            DB::commit();

            return $this->success('partner list is successfully retrived', $partner);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(PartnerStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $payload['password'] = str()->random();
            $payload['status'] = GeneralStatusEnum::ACTIVE->value;

            $emailContent = EmailContent::where([
                'status' => GeneralStatusEnum::ACTIVE->value,
                'content_type' => EmailContentTypeEnum::PARTNER_ACCOUNT_OPENING->value,
            ])->first()->toArray();

            $emailContent['content'] = new HtmlString($emailContent['content']);

            Mail::to($payload['email'])->send(new PartnerAccountEmailTemplate($payload, $emailContent));

            $partner = Partner::create($payload->toArray());
            DB::commit();

            return $this->success('Partner account is successfully created', $partner);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();
        try {

            $partner = Partner::findOrFail($id);
            DB::commit();

            return $this->success('partner account detail is successfully retrived', $partner);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(PartnerUpdateRequest $request, $id)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();
        try {

            $partner = Partner::findOrFail($id);

            if ($payload['kyc_status'] === KycStatusEnum::REJECT->value) {
                $emailContent = EmailContent::where([
                    'status' => GeneralStatusEnum::ACTIVE->value,
                    'content_type' => EmailContentTypeEnum::PARTNER_KYC_REJECT->value,
                ])->first()->toArray();
            }

            if ($payload['kyc_status'] === KycStatusEnum::FULL_KYC->value) {
                $emailContent = EmailContent::where([
                    'status' => GeneralStatusEnum::ACTIVE->value,
                    'content_type' => EmailContentTypeEnum::PARTNER_KYC_APPROVE->value,
                ])->first()->toArray();
            }

            $emailContent['content'] = new HtmlString($emailContent['content']);

            Mail::to($payload['email'])->send(new PartnerAccountEmailTemplate($payload, $emailContent));

            $partner->update($payload->toArray());
            DB::commit();

            return $this->success('partner account is updated successfully', $partner);

        } catch (Exception $e) {
            DB::rollback();
            dd($e);
            throw $e;
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $partner = Partner::findOrFail($id);
            $partner->delete();
            DB::commit();

            return $this->success('partner account is deleted successfully', $partner);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
