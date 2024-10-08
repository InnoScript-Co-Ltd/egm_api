<?php

namespace App\Http\Middleware;

use App\Enums\AgentStatusEnum;
use App\Enums\KycStatusEnum;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AgentMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = auth('agent')->user();

        if ($auth && $auth->kyc_status === KycStatusEnum::FULL_KYC->value && $auth->status === AgentStatusEnum::ACTIVE) {
            return $next($request);
        }

        // if ($auth && $auth->kyc_status !== KycStatusEnum::FULL_KYC->value && $auth->status !== AgentStatusEnum::ACTIVE) {
        //     return new JsonResponse([
        //         'message' => 'Account is not active',
        //         'data' => null,
        //     ]);
        // }

        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', '*')
            ->header('Access-Control-Allow-Credentials', ' false');
        ;
    }
}
