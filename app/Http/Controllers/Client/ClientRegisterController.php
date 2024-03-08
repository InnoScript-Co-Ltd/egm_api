<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\ClientRegisterRequest;
use App\Mail\EmailVerifyCode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Mail;

class ClientRegisterController extends Controller
{
    public function store(ClientRegisterRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $pin = rand(100000, 999999);
            Mail::to($payload['email'])->send(new EmailVerifyCode($pin));

            $payload['email_code'] = $pin;
            $user = User::create($payload->toArray());
            DB::commit();

            return $this->success('new user is successfully created.', $user);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
