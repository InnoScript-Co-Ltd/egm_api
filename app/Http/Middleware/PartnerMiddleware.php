<?php

namespace App\Http\Middleware;

use App\Enums\KycStatusEnum;
use App\Enums\PartnerStatusEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PartnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $auth = auth('partner')->user();

        if ($auth && $auth->kyc_status === KycStatusEnum::FULL_KYC->value && $auth->status === PartnerStatusEnum::ACTIVE) {
            return $next($request);
        }

        return $next($request);
    }
}
