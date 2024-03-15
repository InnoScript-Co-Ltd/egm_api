<?php

namespace App\Http\Controllers\Client;

use App\Enums\UserStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\ClientLoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class ClientAuthController extends Controller
{
    protected function createNewToken($token)
    {
        $auth = auth('api');

        return $this->success('user is successfully signed in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $auth->user(),
        ]);
    }

    public function login(ClientLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = User::where(['email' => $payload['email']])->first();

            if (! $user) {
                return $this->badRequest('Account does not found');
            }

            if ($user->status !== UserStatusEnum::ACTIVE->value) {
                return $this->badRequest('Account is not active');
            }

            $token = auth()->guard('api')->attempt($payload->toArray());

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
