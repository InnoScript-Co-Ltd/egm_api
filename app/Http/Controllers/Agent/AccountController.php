<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\Agents\AccountStoreRequest;
use App\Models\Agent;
use Exception;
use Mail;
use App\Mail\EmailVerifyCode;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class AccountController extends Controller
{
    public function store(AccountStoreRequest $request)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $payload['email_verify_code'] = rand(100000, 999999);
            $payload['email_expired_at'] = Carbon::now()->addMinutes(5);

            $agent = Agent::create($payload->toArray());

            Mail::to($payload['email'])->send(new EmailVerifyCode($payload['email_verify_code']));
            DB::commit();

            return $this->success('Agent is successfully created', $agent);

        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
