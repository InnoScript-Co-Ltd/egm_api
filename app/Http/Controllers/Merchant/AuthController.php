<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Requests\MerchantLoginRequest;
use App\Models\Admin;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Create new token for user login
     */
    protected function createNewToken($token)
    {
        $auth = auth('merchant');

        return $this->success('Merchant is successfully signed in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('merchant')->factory()->getTTL() * 60,
            'user' => $auth->user(),
        ]);
    }

    public function login(MerchantLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $merchant = Admin::where(['email' => $payload['email']])->first();

            if ($merchant === null) {
                return $this->badRequest('Merchant account does not found');
            }

            if ($merchant['status'] !== 'ACTIVE') {
                return $this->badRequest('Merchant account status is '.$merchant['status']);
            }

            if ($merchant['rnp']['role'] === null) {
                return $this->badRequest('merchant account does not have permission');
            }

            $token = auth()->guard('merchant')->attempt($payload->toArray());

            if ($token) {
                return $this->createNewToken($token);
            }

        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function activeRoles()
    {
        DB::beginTransaction();

        try {
            $roles = Role::with(['permissions'])->where(['is_merchant' => true])->get()->toArray();

            $roles = collect($roles)->map(function ($role) {
                $role['permissions'] = collect($role['permissions'])->map(function ($permission) {
                    return $permission['name'];
                });

                return $role;
            })->toArray();

            DB::commit();

            return $this->success('Active role is successfully retrived', $roles);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
