<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailVerificationRequest;
use App\Http\Resources\V1\AuthResource;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifyEmailController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(EmailVerificationRequest $request): JsonResponse
    {

        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {

            return $this->success(
                data: [],
                message: config('messages.auth.email_already_verified'),
                code: Response::HTTP_OK
            );
        }

        if ($user->markEmailAsVerified()) {

            event(new Verified($user));
        }

        $token = $user->createToken(config('sanctum.login_token'))->plainTextToken;

        return $this->success(
            data: [
                'user' => AuthResource::make($user),
                'token' => $token,
            ],
            message: config('messages.auth.email_verified'),
            code: Response::HTTP_OK
        );
    }
}
