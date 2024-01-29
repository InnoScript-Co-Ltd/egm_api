<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MerchantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->header('x-api-key') === null) {
            throw new UnauthorizedException('merchant api key does not found');
        }

        $apikey = $request->header('x-api-key');

        if ($apikey !== env('MERCHANT_API_KEY')) {
            throw new UnauthorizedException('invalid merchant api key');
        }

        return $next($request);
    }
}
