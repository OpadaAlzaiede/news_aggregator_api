<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\V1\AuthResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     summary="user login",
     *     tags={"auth"},
     *
     *     @OA\RequestBody(
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(
     *                     property="email",
     *                     type="email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="password"
     *                 ),
     *                 example={
     *                      "email": "swagger@test.com",
     *                      "password": "password",
     *                 }
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Login success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "data": {"user": {"name": "john doe", "email": "john@doe.com"}, "token": "Rnw5mhtGF7e"},
     *                              "message": "You have logged in successfully."},
     *                  summary="An result object."
     *              ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="failed login",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Examples(
     *                  example="result",
     *                  value={"message": "The provided credentials don't match our records."},
     *                  summary="An result object."
     *             ),
     *          )
     *      )
     * )
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {

        $credentials = $request->validated();

        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {

            return $this->error(
                message: config('messages.auth.invalid_credentials'),
                code: Response::HTTP_UNAUTHORIZED
            );
        }

        $user = Auth::user();
        $token = $user->createToken(config('sanctum.login_token'))->plainTextToken;

        return $this->success(
            data: [
                'user' => AuthResource::make($user),
                'token' => $token,
            ],
            message: config('messages.auth.login_success'),
            code: Response::HTTP_OK
        );
    }
}
