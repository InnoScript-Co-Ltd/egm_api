<?php

namespace App\Http\Controllers\Web;

use App\Enums\UserStatusEnum;
use App\Http\Requests\WebUserLoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WebAuthController extends WebController
{
    /**
     * APIs for user login
     *
     * @bodyParam username required.
     * @bodyParam password required.
     */
    public function login(WebUserLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            if (isset($payload['phone'])) {
                $user = User::where(['phone' => $payload['phone']])->first();
            }

            if (isset($payload['email'])) {
                $user = User::where(['email' => $payload['email']])->first();
            }

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

            return $this->badRequest('Incorrect name and password');

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * APIs for user login out
     */
    public function logout()
    {
        DB::beginTransaction();

        try {
            $user = auth()->guard('api')->user();

            DB::commit();

            if ($user) {
                auth()->guard('api')->logout();

                return $this->success('User successfully signed out', null);
            }

            return $this->badRequest('Invalid token for logout');

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * APIs for refresh token
     */
    public function refresh()
    {
        DB::beginTransaction();
        try {
            $user = auth()->guard('api')->user();
            DB::commit();

            if ($user) {
                return $this->createNewToken(auth()->guard('api')->refresh());
            }

            return $this->badRequest('Invalid token');

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Create new token for user login
     */
    protected function createNewToken($token)
    {
        return $this->success('User successfully signed in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user(),
        ]);
    }
}
