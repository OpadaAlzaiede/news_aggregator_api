<?php

namespace App\Http\Controllers\Api\V1;

use App\Traits\Pagination;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\V1\Articles\IndexResource;
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
    /**
     * @OA\Get(
     *     path="/api/v1/feed",
     *     summary="show feed",
     *     tags={"articles"},
     *     security={ {"sanctum": {} }},
     *      @OA\Parameter(
     *          name="perPage",
     *          in="query",
     *          required=false,
     *          description="number of articles per page",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="page number",
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "data": {"slug": "article1", "title": "article1", "description": "Laborum nesciunt ..", "content": "etur minus architect ..",
     *                              "author": "r King", "category": "science", "source": "Newscred", "published_at": "2020-01-01"},
     *                              "links": {"first": "articles?page=1", "next": "articles?page=2"},
     *                              "meta": {"current_page": 1, "from": 1, "path": "/articles", "per_page": 10, "to": 10}
     *                  },
     *                  summary="An result object."
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="user doesn't have preferences.",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={"message": "user doesn't have preferences."},
     *                  summary="An result object."
     *             ),
     *          )
     *      )
     * )
     */
    public function index(): AnonymousResourceCollection {

        $feed = Auth::user()->feed()
                    ->select(['title', 'slug', 'description', 'category', 'author', 'source', 'published_at'])
                    ->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        return IndexResource::collection($feed);
    }
}
