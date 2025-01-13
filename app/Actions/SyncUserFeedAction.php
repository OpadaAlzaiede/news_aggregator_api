<?php

namespace App\Actions;

use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Str;
use App\Models\UserPreference;
use App\Enums\PreferenceForEnum;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;

class SyncUserFeedAction {

    /**
     * This method syncs the users preferred articles
     *
     * @param User $user
     */
    public function handle(User $user): void {

        $articleIds = [];

        foreach(PreferenceForEnum::cases() as $case) { // This has time complexity of O(1) as cases are limited.

            $preferencesByType = $this->filterPreferencesByType($user, $case->value);

            $columnName = Str::lower($case->name);

            if(Schema::hasColumn('articles', $columnName)) {

                foreach($preferencesByType as $type) { // This also has time complexity of O(1) as we limited user's preferences count.

                    $preferredArticleIds = Article::query()
                                    ->select(['id'])
                                    ->where('published_at', '>', now()->subYear())
                                    ->where($columnName, 'LIKE', '%'. $type->preference_value . '%') // No worries with this search as we are using date filter.
                                    ->limit(10)
                                    ->pluck('id')
                                    ->toArray();

                    $articleIds = array_merge($articleIds, $preferredArticleIds);
                }
            }
        }

        $user->feed()->sync($articleIds);
    }

    /**
     * This method filter users preferences by preference type
     *
     * @param User $user
     * @param int $value
     *
     * @return Collection
     */
    private function filterPreferencesByType(User $user, int $value): Collection {

        return $user->preferences->filter(function(UserPreference $preference) use($value) {
            return $preference->preference_type == $value;
        });
    }
}
