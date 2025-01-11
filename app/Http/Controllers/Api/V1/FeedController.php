<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\Pagination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Article\IndexResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FeedController extends Controller
{
    use Pagination;

    public function __construct(Request $request) {

        $this->setPaginationParams($request);
    }


    /**
     * Handle the incoming request.
     *
     * @param Request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function __invoke(): AnonymousResourceCollection {

        $feed = Auth::user()->feed()
                    ->select(['title', 'slug', 'description', 'category', 'author', 'source', 'published_at'])
                    ->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        return IndexResource::collection($feed);
    }
}
