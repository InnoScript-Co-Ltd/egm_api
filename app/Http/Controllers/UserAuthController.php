<?php

namespace App\Http\Controllers;

use App\Enums\UserStatusEnum;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserAuthController extends Controller
{
    /**
     * APIs for user login
     *
     * @bodyParam username required.
     * @bodyParam password required.
     */
    public function login(LoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = User::where([
                'name' => $payload['name'],
            ])->first();

            if (! $user) {
                return $this->validationError('Login failed', [
                    'message' => ['Incorrect name and password'],
                ]);
            }

            if ($user->status !== UserStatusEnum::ACTIVE->value) {
                return $this->validationError('Login failed', [
                    'message' => ['Account is not active'],
                ]);
            }

            $token = auth()->attempt($payload->toArray());
            DB::commit();

            if ($token) {
                return $this->createNewToken($token);
            }

            return $this->validationError('Login failed', [
                'message' => ['Incorrect name and password'],
            ]);

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
            $user = auth()->user();
            DB::commit();

            if ($user) {
                auth()->logout();

                return $this->success('User successfully signed out', null);
            }

            return $this->validationError('Login failed', [
                'message' => ['Invalid token for logout'],
            ]);

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
        try {
            $user = auth()->user();
            DB::commit();

            if ($user) {
                return $this->createNewToken(auth()->refresh());
            }

            return $this->validationError('Login failed', [
                'message' => ['Invalid token'],
            ]);

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
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
}
