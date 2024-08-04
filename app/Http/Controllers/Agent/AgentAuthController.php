<?php

namespace App\Http\Controllers\Agent;

use App\Enums\AgentStatusEnum;
use App\Http\Controllers\Dashboard\Controller;
use App\Http\Requests\AgentAuthLoginRequest;
use App\Models\Agent;
use Exception;
use Illuminate\Support\Facades\DB;

class AgentAuthController extends Controller
{
    protected function createNewToken($token)
    {
        $id = auth('agent')->user()->id;

        $user = Agent::findOrFail($id);

        return $this->success('User is successfully signed in', [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60,
            'user' => $user,
        ]);
    }

    public function profile()
    {
        DB::beginTransaction();

        try {
            $agent = auth('agent')->user();

            if ($agent) {
                return $this->success('Agent is successfully signed in', $agent->toArray());
            } else {
                $this->unauthenticated('Please login again');
            }

        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function login(AgentAuthLoginRequest $request)
    {
        $payload = collect($request->validated());

        DB::beginTransaction();

        try {
            $user = Agent::where(['email' => $payload['email']])->first();

            if (! $user) {
                return $this->badRequest('Account does not found');
            }

            if ($user->status !== AgentStatusEnum::ACTIVE->value) {
                return $this->badRequest('Account is not active');
            }

            $token = auth()->guard('agent')->attempt($payload->toArray());

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
