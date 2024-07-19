<?php

namespace App\Http\Controllers\Agent;

use App\Enums\InvestorStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\InvestorResendCodeRequest;
use App\Http\Requests\Agents\InvestorStoreRequest;
use App\Http\Requests\Agents\InvestorVerifyRequest;
use App\Mail\EmailVerifyCode;
use App\Models\Investor;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Mail;

class InvestorController extends Controller
{
    public function index()
    {
        $id = auth('agent')->user()->id;

        DB::beginTransaction();

        try {
            $investors = Investor::where(['agent_id' => $id])->get();
            DB::commit();

            return $this->success('investor list is successfully retrived', $investors);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function store(InvestorStoreRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();
        try {

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
            Mail::to($payload['email'])->send(new EmailVerifyCode($payload['email_verify_code']));

            $investor = Investor::create($payload->toArray());

            DB::commit();

            return $this->success('New investor is created successfully', $investor);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function verifyCode(InvestorVerifyRequest $request)
    {

        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $investor = Investor::findOrFail($payload['investor_id']);

            $verifyCode = $investor['email_verify_code'];
            $expiredAt = $investor['email_expired_at'];

            if ($investor['status'] === InvestorStatusEnum::ACTIVE->value) {
                return $this->badRequest('account is already verified');
            }

            $now = Carbon::now();
            $checkExpired = Carbon::now()->between($now, $expiredAt);

            if ($checkExpired === false) {
                return $this->badRequest('Email verify code is expired');
            }

            if ($verifyCode !== $payload['email_verify_code']) {
                return $this->badRequest('Email verify code is does not match');
            }

            $updatePayload['email_verify_code'] = null;
            $updatePayload['email_verified_at'] = Carbon::now();
            $updatePayload['status'] = InvestorStatusEnum::CHECKING->value;

            $investor->update($updatePayload);
            DB::commit();

            return $this->success('your email is successfully verified', null);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function resendCode(InvestorResendCodeRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $investor = Investor::where(['email' => $payload['email']])->first();

            if ($investor !== null) {
                $updatePayload['email_verify_code'] = rand(100000, 999999);
                $updatePayload['email_expired_at'] = Carbon::now()->addMinutes(5);
                $investor->update($updatePayload);

                Mail::to($payload['email'])->send(new EmailVerifyCode($updatePayload['email_verify_code']));
                DB::commit();

                return $this->success('email verify code is resend successfully', $investor);
            }

            return $this->badRequest('Your email could not be found');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $investor = Investor::findOrFail($id);
            DB::commit();

            return $this->success('investor detail is successfully retrived', $investor);

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
