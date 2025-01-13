<?php

namespace App\Http\Controllers\Api\V1;


use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreUserPreferenceRequest;
use App\Services\UserPreferencesService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserPreferenceController extends Controller
{

    public function __construct(private UserPreferencesService $userPreferencesService)
    {
        //
    }
    /**
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    /**
     * @OA\Get(
     *     path="/api/v1/preferences",
     *     summary="user preferences",
     *     tags={"preferences"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "data": {"preference_type": "author", "Koelpin"}
     *                  },
     *                  summary="An result object."
     *             ),
     *         )
     *     ),
     * )
     */
    public function index(): AnonymousResourceCollection{

        return $this->userPreferencesService->index();
    }

    /**
     * @param StoreUserPreferenceRequest $request
     *
     * @return JsonResponse
     */
    /**
     * @OA\Post(
     *     path="/api/v1/preferences",
     *     summary="set user preferences",
     *     tags={"preferences"},
     *     security={ {"sanctum": {} }},
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="preferences",
     *                     type="array",
     *                     @OA\Items(
     *                          @OA\Property(
     *                              property="preference_type",
     *                              type="enum",
     *                              example=1
     *                          ),
     **                          @OA\Property(
     *                              property="preference_value",
     *                              type="string",
     *                              example="Koelpin"
     *                          ),
     *                     ),
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "data": {},
     *                      "message" :"Your preferences have been set successfully."
     *                  },
     *                  summary="An result object."
     *             ),
     *         )
     *     ),
     * )
     */
    public function store(StoreUserPreferenceRequest $request): JsonResponse {

        $preferences = $request->validated('preferences');

        try {

            $this->userPreferencesService->store($preferences);

            return $this->success(
                data:[],
                message: config('messages.preferences.preferences_set_successfully'),
                code: Response::HTTP_CREATED
            );
        } catch(\Throwable $e) {

            return $this->error(
                message: config('messages.preferences.preferences_set_failed'),
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     *
     * @return JsonResponse
     */
    /**
     * @OA\Delete(
     *     path="/api/v1/preferences",
     *     summary="delete user preferences",
     *     tags={"preferences"},
     *     security={ {"sanctum": {} }},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "data": {},
     *                      "message": "Your preferences have been deleted successfully"
     *                  },
     *                  summary="An result object."
     *             ),
     *         )
     *     ),
     * )
     */
    public function destroy(): JsonResponse {

        try {

            $this->userPreferencesService->destroy();

            return $this->success(
                data:[],
                message: config('messages.preferences.preferences_deleted_successfully'),
                code: Response::HTTP_OK
            );
        } catch(\Throwable $e) {

            return $this->error(
                message: config('messages.preferences.preferences_deletion_failed'),
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
