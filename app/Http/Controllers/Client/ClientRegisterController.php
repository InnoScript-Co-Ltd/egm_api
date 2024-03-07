<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\ClientRegisterRequest;
use App\Mail\SendVerifiedCode;
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
            Mail::to($payload['email'])->send(new SendVerifiedCode());
            $user = User::create($payload->toArray());
            DB::commit();

            return $this->success('new user is successfully created.', $user);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
