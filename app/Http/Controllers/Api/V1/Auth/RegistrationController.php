<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\V1\AuthResource;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     summary="user registration",
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
     *                     property="name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="password"
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     type="password"
     *                 ),
     *                 example={
     *                      "name": "john doe",
     *                      "email": "john@doe.com",
     *                      "password": "re412##AB",
     *                      "password_confirmation": "re412##AB"
     *                 }
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Registration success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "data": {"user": {"name": "john doe", "email": "john@doe.com"}, "token": "Rnw5mhtGF7e"},
     *                              "message": "Registered successfully."},
     *                  summary="An result object."
     *              ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Examples(
     *                  example="result",
     *                  value={"success": false, "data": null, "errors": {"email": {"The email field must be a valid email address."}}},
     *                  summary="An result object."
     *             ),
     *          )
     *      )
     * )
     */
    public function __invoke(RegistrationRequest $request): JsonResponse
    {

        $user = User::create($request->validated()); /* The password field will be hashed automatically. */

        $user->notify(new VerifyEmail);

        return $this->success(
            data: [
                'user' => AuthResource::make($user),
            ],
            message: config('messages.auth.register_success'),
            code: Response::HTTP_CREATED
        );
    }
}
