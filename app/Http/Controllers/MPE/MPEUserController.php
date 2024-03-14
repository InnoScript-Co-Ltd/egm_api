<?php

namespace App\Http\Controllers\MPE;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\MPEUserLoginRequest;
use App\Models\MPEUser;
use Exception;
use Illuminate\Support\Facades\DB;

class MPEUserController extends Controller
{
    public function login(MPEUserLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = MPEUser::where(['email' => $payload['email']])->first();

            if (! $user) {
                return $this->badRequest('Account does not found');
            }

            if ($user->status !== UserStatusEnum::ACTIVE->value) {
                return $this->badRequest('Account is not active');
            }

            $token = auth()->guard('mpe')->attempt($payload->toArray());

            DB::commit();

            if ($token) {
                return $this->createNewToken($token);
            }

            return $this->badRequest('Incorrect email and passwrod');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
