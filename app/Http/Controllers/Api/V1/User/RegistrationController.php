<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\V1\AuthResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;


class RegistrationController extends Controller
{
    /**
     * @param RegistrationRequest $request
     *
     * @return JsonResponse
     */
    public function __invoke(RegistrationRequest $request): JsonResponse {

        $user = User::create($request->validated()); /* The password field will be hashed automatically.*/

        $token = $user->createToken(config('sanctum.login_token'))->plainTextToken;

        return $this->success([
            'user' => AuthResource::make($user),
            'token' => $token
        ], config('messages.auth.register_success'), Response::HTTP_CREATED);
    }
}
