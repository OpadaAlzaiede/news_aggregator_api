<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireJsonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->is('api/*') && !$request->wantsJson()) {

            return response()->json([
                'message' => 'Please request with HTTP header: Accept: application/json'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        return $next($request);
    }
}
