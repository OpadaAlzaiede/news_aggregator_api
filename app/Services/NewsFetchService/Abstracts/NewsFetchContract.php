<?php

namespace App\Services\NewsFetchService\Abstracts;

use Carbon\Carbon;

interface NewsFetchContract {

    public function fetchArticles(?string $keyword, Carbon $syncFrom);
}
