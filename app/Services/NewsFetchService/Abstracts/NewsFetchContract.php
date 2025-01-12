<?php

namespace App\Services\NewsFetchService\Abstracts;

use Carbon\Carbon;

interface NewsFetchContract {

    /**
     * @param ?string $keyword
     * @param ?Carbon $syncFrom
     */
    public function fetchArticles(?string $keyword, ?Carbon $syncFrom);
}
