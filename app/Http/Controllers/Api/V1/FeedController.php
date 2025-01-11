<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\Pagination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\ArticleResource;
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
                    ->select([
                        'title',
                        'slug',
                        'description',
                        DB::raw("CASE WHEN CHAR_LENGTH(content) > 100
                            THEN CONCAT(SUBSTRING(content, 1, 100), '... [+', CHAR_LENGTH(content) - 100, ' chars]')
                            ELSE content
                            END AS content"
                        ),
                        'category',
                        'author',
                        'source',
                        'published_at'
                    ])
                    ->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        return ArticleResource::collection($feed);
    }
}
