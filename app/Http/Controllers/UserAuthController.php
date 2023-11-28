<?php

namespace App\Http\Controllers;

use App\Enums\UserStatusEnum;
use App\Http\Requests\ResendVerifiedCodeRequest;
use App\Http\Requests\UserLoginRequest;
use App\Mail\VerifiedCode as MailVerifiedCode;
use App\Models\User;
use App\Models\VerifiedCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class UserAuthController extends Controller
{
    /**
     * APIs for user login
     *
     * @bodyParam username required.
     * @bodyParam password required.
     */
    public function login(UserLoginRequest $request)
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

            $token = auth()->attempt($payload->toArray());
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
            $user = auth()->user();
            DB::commit();

            if ($user) {
                auth()->logout();

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
     * Resend user account verification code by email and phone number
     *
     * @param type [email, phone]
     */
    public function ResendVerifiedCode(ResendVerifiedCodeRequest $request, $type)
    {
        $payload = collect($request->validated());
        DB::beginTransaction();

        try {
            $user = User::FindOrFail($payload['id']);
            $generateCode = VerifiedCode::create([
                'user_id' => $user->id,
                'code' => rand(100000, 999999),
            ]);
            $send = Mail::to($user->email)->send(new MailVerifiedCode());
            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
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
