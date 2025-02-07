<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogoutController extends Controller
{
    /**
     * @return JsonResponse
     */
    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     summary="user logout",
     *     tags={"auth"},
     *    security={ {"sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="Logout success",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={"message": "You have logged out successfully."},
     *                  summary="An result object."
     *             ),
     *          )
     *      )
     * )
     */
    public function __invoke()
    {
        $user = Auth::user();
        $user->tokens()->delete();

        return $this->success(data: [], message: config('messages.auth.logout_success'), code: Response::HTTP_OK);
    }
}
