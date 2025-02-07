<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Articles\IndexResource;
use App\Http\Resources\V1\Articles\ShowResource;
use App\Models\Article;
use App\Services\ArticlesService;
use App\Traits\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleController extends Controller
{
    use Pagination;

    public function __construct(protected ArticlesService $articlesService, Request $request)
    {

        $this->setPaginationParams($request);
    }

    /**
     * Return a list of articles
     */
    /**
     * @OA\Get(
     *     path="/api/v1/articles",
     *     summary="articles resource",
     *     tags={"articles"},
     *     security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(
     *          name="keyword",
     *          in="query",
     *          required=false,
     *          description="Global search keyword",
     *
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     @OA\Parameter(
     *          name="published_at",
     *          in="query",
     *          required=false,
     *          description="The publish date of the article",
     *
     *          @OA\Schema(
     *              type="date"
     *          ),
     *     ),
     *
     *      @OA\Parameter(
     *          name="category",
     *          in="query",
     *          required=false,
     *          description="The category of the article",
     *
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *      @OA\Parameter(
     *          name="source",
     *          in="query",
     *          required=false,
     *          description="The source of the article",
     *
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *      @OA\Parameter(
     *          name="perPage",
     *          in="query",
     *          required=false,
     *          description="number of articles per page",
     *
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *
     *      @OA\Parameter(
     *          name="page",
     *          in="query",
     *          required=false,
     *          description="page number",
     *
     *          @OA\Schema(
     *              type="number"
     *          ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Examples(
     *                  example="result",
     *                  value={
     *                      "data": {"slug": "article1", "title": "article1", "description": "Laborum nesciunt ..", "content": "etur minus architect ..",
     *                              "author": "r King", "category": "science", "source": "Newscred", "published_at": "2020-01-01"},
     *                              "links": {"first": "articles?page=1", "next": "articles?page=2"},
     *                              "meta": {"current_page": 1, "from": 1, "path": "/articles", "per_page": 10, "to": 10}
     *                  },
     *                  summary="An result object."
     *              ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Examples(
     *                  example="result",
     *                  value={"message": "unauthenticated."},
     *                  summary="An result object."
     *             ),
     *          )
     *      )
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {

        $articles = $this->articlesService->getArticles(
            $this->perPage,
            $this->page,
            $request->string('keyword'),
            $request->date('published_at'),
            $request->string('category'),
            $request->string('source')
        );

        return IndexResource::collection($articles);
    }

    /**
     * Return a list of articles
     *
     * @return AnonymousResourceCollection
     */
    /**
     * @OA\Get(
     *     path="/api/v1/articles/{article}",
     *     summary="show article",
     *     tags={"articles"},
     *     security={ {"sanctum": {} }},
     *
     *      @OA\Parameter(
     *          name="article",
     *          in="path",
     *          required=false,
     *          description="article slug",
     *
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Examples(
     *                  example="result",
     *                  value={"slug": "totam", "title": "Totam", "description": "Vel quos occaecati", "content": "Iure et vero facere ",
     *                          "author": "Prof. Deven Brei", "category": "Excepturi", "source": "Sint", "published_at": "1987-05-02"},
     *                  summary="An result object."
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="record not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Examples(
     *                  example="result",
     *                  value={"message": "record not found."},
     *                  summary="An result object."
     *             ),
     *          )
     *      )
     * )
     */
    public function show(Article $article): JsonResource
    {

        return ShowResource::make($article);
    }
}
