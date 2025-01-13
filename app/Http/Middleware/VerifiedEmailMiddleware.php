<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedEmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::where('email', $request->get('email'))->first();

        if($user) {

            if(Auth::attempt(['email' => $user->email, 'password' => $request->get('password')])) {

                if(is_null($user->email_verified_at)) {

                    return response()->json([
                        'message' => config('messages.auth.email_verify')
                    ], Response::HTTP_UNAUTHORIZED);
                }
            }
        } else {

            return response()->json([
                'message' => config('messages.auth.invalid_credentials'),
            ] , Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
