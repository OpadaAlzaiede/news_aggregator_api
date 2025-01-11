<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\ArticleResource;
use App\Traits\Pagination;
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
                    ->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        return ArticleResource::collection($feed);
    }
}
