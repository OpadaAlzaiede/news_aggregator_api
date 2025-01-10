<?php

namespace App\Http\Controllers\Api\V1;

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
    public function store(StoreUserPreferenceRequest $request): JsonResponse {

        try {

            $user = Auth::user();

            DB::beginTransaction();

            $user->preferences()->delete();

            $preferences = $request->validated('preferences');

            foreach($preferences as $preference) {

                UserPreference::create([
                    'user_id' => $user->id,
                    'preference_type' => $preference['preference_type'],
                    'preference_value' => $preference['preference_value']
                ]);
            }

            DB::commit();

            Cache::forget('user_preferences'.$user->id);
            SyncUserFeedJob::dispatch($user);

            return $this->success(
                data:[],
                message: config('messages.preferences_set_successfully'),
                code: Response::HTTP_CREATED
            );
        } catch(\Throwable $e) {
            DB::rollBack();

            return $this->error(
                message: config('messages.preferences_set_failed'),
                code: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     *
     * @return JsonResponse
     */
    public function destroy(): JsonResponse {

        $user = Auth::user();

        UserPreference::where('user_id', $user->id)->delete();
        $user->feed()->delete();

        Cache::forget('user_preferences'.$user->id);

        return $this->success(
            data:[],
            message: config('messages.preferences_deleted_successfully'),
            code: Response::HTTP_CREATED
        );
    }
}
