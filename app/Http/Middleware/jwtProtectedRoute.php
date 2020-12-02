<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class jwtProtectedRoute extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $usuario = JWTAuth::parseToken()->authenticate();
            $usuario->group;
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException)
                return response()->json(['status' => 'Token Invalido'], 401);
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException)
                return response()->json(['status' => 'Sessão Expirada'], 401);

            return response()->json(['status' => 'token não informado'], 401);
        }

        $request->usuario = $usuario;
        $request->user = $usuario;
        return $next($request);
    }
}
