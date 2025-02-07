<?php

namespace App\Services;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;

class FeedService
{
    public function getFeed(
        int $perPage,
        int $page,
    ): Paginator {

        return Auth::user()->feed()
            ->select(['title', 'slug', 'description', 'category', 'author', 'source', 'published_at'])
            ->simplePaginate($perPage, ['*'], 'page', $page);
    }
}
