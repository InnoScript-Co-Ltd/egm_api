<?php

namespace App\Http\Controllers\Dashboard;

use App\Enums\AdminStatusEnum;
use App\Http\Requests\AdminLoginRequest;
use App\Models\Admin;
use Exception;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    protected $dbs;

    public function __construct()
    {
        $this->dbs = [
            'gscexport' => env('GSCEXPORT_DATABASE'),
            'mpe' => env('MPE_DATABASE'),
        ];
    }

    /**
     * APIs for user login
     *
     * @bodyParam email required.
     * @bodyParam password required.
     */
    public function login(AdminLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::connection($this->dbs['gscexport'])->beginTransaction();

        try {
            $admin = Admin::where(['email' => $payload['email']])->first();

            if (! $admin) {
                return $this->badRequest('Account does not found');
            }

            if ($admin->status !== AdminStatusEnum::ACTIVE->value) {
                return $this->badRequest('Account is not active');
            }

            $token = auth()->guard('dashboard')->attempt($payload->toArray());

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
        try {
            $user = auth()->user();
            DB::commit();

            if ($user) {
                return $this->createNewToken(auth()->refresh());
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
        $auth = auth('dashboard');

        return $this->success('Admin successfully signed in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('dashboard')->factory()->getTTL() * 60,
            'user' => $auth->user(),
        ]);
    }
}
