<?php

namespace App\Services;

use App\Enums\HasPreferencesEnum;
use App\Jobs\SyncUserFeedJob;
use App\Models\UserPreference;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserPreferencesService
{
    public function getPreferences(): Collection
    {

        return UserPreference::query()
            ->select(['preference_type', 'preference_value'])
            ->where('user_id', Auth::id())
            ->when(request('preference_type'), function ($query, $preference_type) {
                $query->where('preference_type', $preference_type);
            })
            ->get();
    }

    public function store(array $preferences): void
    {

        $user = Auth::user();

        $preferencesArray = $this->buildPreferencesArray($preferences, $user->id);

        try {

            DB::beginTransaction();

            $user->preferences()->delete();
            $user->update(['has_preferences' => HasPreferencesEnum::YES->value]);

            UserPreference::insert($preferencesArray);

            DB::commit();

            Cache::forget('user_preferences'.$user->id);
            SyncUserFeedJob::dispatch($user);

        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function destroy(): void
    {

        $user = Auth::user();

        try {

            DB::beginTransaction();

            $user->preferences()->delete();
            $user->feed()->delete();
            $user->update(['has_preferences' => HasPreferencesEnum::NO->value]);

            DB::commit();

            Cache::forget('user_preferences'.$user->id);

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Setup the preferences array to bulk insert it in the DB
     */
    private function buildPreferencesArray(array $preferences, int $userId): array
    {

        $preferencesArray = [];

        foreach ($preferences as $preference) {

            $preferencesArray[] = [
                'preference_type' => $preference['preference_type'],
                'preference_value' => $preference['preference_value'],
                'user_id' => $userId,
            ];
        }

        return $preferencesArray;
    }
}
