<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\HasPreferencesEnum;
use App\Models\UserPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreUserPreferenceRequest;
use App\Http\Resources\V1\UserPreferenceResource;
use App\Jobs\SyncUserFeedJob;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserPreferenceController extends Controller
{
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

        $user = Auth::user();

        $preferences = Cache::rememberForever('user_preferences'.$user->id, function() {

            return UserPreference::query()
                        ->select(['preference_type', 'preference_value'])
                        ->where('user_id', Auth::id())
                        ->when(request('preference_type'), function($query, $preference_type) {
                            $query->where('preference_type', $preference_type);
                        })
                        ->get();
        });

        return UserPreferenceResource::collection($preferences);
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

        $user = Auth::user();
        $preferences = $request->validated('preferences');
        $preferencesArray = $this->buildPreferencesArray($preferences, $user->id);

        try {

            DB::beginTransaction();

            $user->preferences()->delete();
            $user->update(['has_preferences' => HasPreferencesEnum::YES->value]);

            UserPreference::insert($preferencesArray);

            Cache::forget('user_preferences'.$user->id);
            SyncUserFeedJob::dispatch($user);

            DB::commit();

            return $this->success(
                data:[],
                message: config('messages.preferences.preferences_set_successfully'),
                code: Response::HTTP_CREATED
            );
        } catch(\Throwable $e) {
            DB::rollBack();

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

        $user = Auth::user();

        try {

            DB::beginTransaction();

            $user->preferences()->delete();
            $user->feed()->delete();
            $user->update(['has_preferences' => HasPreferencesEnum::NO->value]);

            Cache::forget('user_preferences'.$user->id);

            DB::commit();

            return $this->success(
                data:[],
                message: config('messages.preferences.preferences_deleted_successfully'),
                code: Response::HTTP_OK
            );
        } catch(\Throwable $e) {

            DB::rollBack();

            return $this->error(
                message: config('messages.preferences.preferences_deletion_failed'),
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Setup the preferences array to bulk insert it in the DB
     *
     * @param array $preferences
     * @param int $userId
     *
     * @return array
     */
    private function buildPreferencesArray(array $preferences, int $userId): array {

        $preferencesArray = [];

        foreach($preferences as $preference) {

            $preferencesArray[] = [
                'preference_type' => $preference['preference_type'],
                'preference_value' => $preference['preference_value'],
                'user_id' => $userId
            ];
        }

        return $preferencesArray;
    }
}
