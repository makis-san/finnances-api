<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class OptionalJwt extends BaseMiddleware
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
            $usuario = null;
        }

        $request->usuario = $usuario;
        $request->user = $usuario;
        return $next($request);
    }
}
