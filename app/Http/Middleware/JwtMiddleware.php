<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->header('authorization') === null) {
            throw new UnauthorizedException('token is not found');
        } else {
            try {
                $user = JWTAuth::parseToken()->authenticate();

                return $next($request);
            } catch (Exception $e) {
                throw new UnauthorizedException('CAN NOT AUTHORIZE');
            }
        }
    }
}
