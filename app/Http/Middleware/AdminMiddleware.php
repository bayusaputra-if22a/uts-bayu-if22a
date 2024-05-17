<?php

namespace App\Http\Middleware;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $jwt = $request->bearerToken();

        if (!$jwt) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        try {
            $decoded = JWT::decode($jwt, new Key(env('JWT_SECRET_KEY'), 'HS256'));
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }

        if ($decoded->role != 'admin') {
            return response()->json(['message' => 'access denied'], 403);
        }

        return $next($request);
    }
}
