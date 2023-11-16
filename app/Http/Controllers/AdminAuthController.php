<?php

namespace App\Http\Controllers;

use App\Enums\AdminStatusEnum;
use App\Http\Requests\LoginRequest;
use App\Models\Admin;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
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
            $user = Admin::where([
                'name' => $payload['name'],
            ])->first();

            if (! $user) {
                return $this->badRequest('Incorrect username and password');
            }

            if ($user->status !== AdminStatusEnum::ACTIVE->value) {
                return $this->badRequest('Account is not active');
            }

            $token = auth()->attempt($payload->toArray());
            DB::commit();

            if ($token) {
                return $this->createNewToken($token);
            }

            return $this->badRequest("Incorrect username and passwrod");

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
            $admin = auth()->user();
            DB::commit();

            if ($admin) {
                auth()->logout();

                return $this->success('Admin successfully signed out', null);
            }

            return $this->validationError('Login failed', [
                'message' => ['invalid token for logout'],
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
                'message' => ['invalid token'],
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
        return $this->success('Admin successfully signed in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
}
