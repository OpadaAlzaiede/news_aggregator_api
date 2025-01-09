<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;
use App\Models\PasswordResetToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\PasswordUpdateRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\CustomPasswordResetNotification;

class PasswordResetController extends Controller
{
    /**
     * @param PasswordResetRequest $request
     *
     * @return JsonResponse
     */
    /**
     * @OA\Post(
     *     path="/api/v1/forgot-password",
     *     summary="user reset password",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="email"
     *                 ),
     *                 example={
     *                      "email": "john@doe.com"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Link sent successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "data": {}, "message": "A reset link has been sent to your email."},
     *                  summary="An result object."
     *              ),
     *         )
     *     ),
     *      @OA\Response(
     *         response=429,
     *         description="Too many attempts",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "message": "Too Many Attempts."},
     *                  summary="An result object."
     *              ),
     *         )
     *     ),
     * )
     */
    public function sendLink(ForgotPasswordRequest $request): JsonResponse {

        $user = User::where('email', $request->validated('email'))->first();
        $token = Str::random(config('app.reset_password_token_length'));

        PasswordResetToken::create([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now()
        ]);

        $user->notify(new CustomPasswordResetNotification($token));

        return $this->success(
            data: [],
            message: config('messages.auth.reset_link_sent'),
            code: Response::HTTP_OK
        );
    }

    /**
     * @param PasswordUpdateRequest $request
     *
     * @return JsonResponse
     */
    /**
     * @OA\Post(
     *     path="/api/v1/reset-password",
     *     summary="user reset password",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="email"
     *                 ),
     *                 @OA\Property(
     *                     property="token",
     *                     type="email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="email"
     *                 ),
     *                 example={
     *                      "email": "john@doe.com", "token": "ki8191kaa", "password": "Pww123##pp"
     *                 }
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password updated successfully",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "data": {}, "message": "Your password has been updated successfully."},
     *                  summary="An result object."
     *              ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result-1",
     *                  value={"success": false, "data": null, "errors": {"token": {"Reset link is invalid or has expired."}}},
     *                  summary="invalid token"
     *             ),
     *              @OA\Examples(
     *                  example="result-2",
     *                  value={"success": false, "data": null, "errors": {"email": {"Invalid email."}}},
     *                  summary="invalid email"
     *             ),
     *          )
     *      ),
     * )
     */
    public function reset(PasswordResetRequest $request): JsonResponse {

        $user = User::where('email', $request->validated('email'))->first();

        $user->update(['password' => Hash::make($request->get('password'))]);

        PasswordResetToken::where('email', $user->email)->delete();

        return $this->success(
            data: [],
            message: config('messages.auth.password_update_success'),
            code: Response::HTTP_OK
        );
    }
}
