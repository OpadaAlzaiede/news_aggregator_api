<?php

namespace App\Actions;

use App\Enums\PreferenceForEnum;
use App\Models\UserPreference;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GetMostPopularPreferenceAction
{
    /**
     * This method get's the most popular preference within $from period of time by random preference type.
     */
    public function handle(Carbon $from): ?string
    {

        $selectPreferenceFor = collect(PreferenceForEnum::cases())->shuffle()->first()?->value;

        $mostPopularPreference = UserPreference::select(['preference_value', DB::raw('COUNT(*) as frequency')])
            ->where('preference_type', $selectPreferenceFor)
            ->where('created_at', '>=', $from)
            ->groupBy('preference_value')
            ->orderByDesc('frequency')
            ->first();

        return $mostPopularPreference?->preference_value;
    }
}
