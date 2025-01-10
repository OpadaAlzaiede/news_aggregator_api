<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ArticleResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ArticleController extends Controller
{
    private int $perPage;
    private int $page;

    public function __construct(Request $request) {

        $this->perPage = $request->input('perPage', 10);
        $this->page = $request->input('page', 1);
    }

    /**
     * Return a list of articles
     *
     * @return AnonymousResourceCollection
     */
    /**
     * @OA\Get(
     *     path="/api/v1/articles",
     *     summary="articles resource",
     *     @OA\Parameter(
     *          name="date",
     *          in="query",
     *          required=false,
     *          description="The date of the article",
     *          @OA\Schema(
     *              type="date"
     *          ),
     *     ),
     *      @OA\Parameter(
     *          name="category",
     *          in="query",
     *          required=false,
     *          description="The category of the article",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
     *      @OA\Parameter(
     *          name="source",
     *          in="query",
     *          required=false,
     *          description="The source of the article",
     *          @OA\Schema(
     *              type="string"
     *          ),
     *     ),
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
     *              ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="unauthenticated",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                  example="result",
     *                  value={"message": "unauthenticated."},
     *                  summary="An result object."
     *             ),
     *          )
     *      )
     * )
     */
    public function index(): AnonymousResourceCollection {

        $articles = Article::query()
                    ->when(request('date'), function($query, $date) {
                        $query->whereDate('published_at', $date);
                    })
                    ->when(request('category'), function($query, $category) {
                        $query->where('category', 'LIKE', $category . '%');
                    })
                    ->when(request('source'), function($query, $source) {
                        $query->where('source', 'LIKE', $source . '%');
                    })
                    ->latest('id')
                    ->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        return ArticleResource::collection($articles);
    }

    /**
     * Return a list of articles with keyword search
     *
     * @return AnonymousResourceCollection
     */
    public function indexByKeyword() {

        $articles = Article::query()
                    ->when(request('keyword'), function($query, $keyword) {
                        $query->whereFullText(
                            ['title', 'description', 'content'],
                            $keyword,
                            ['mode' => 'boolean']
                        );
                    }, function($query) {
                        $query->latest('id');
                    })
                    ->simplePaginate($this->perPage, ['*'], 'page', $this->page);

        return ArticleResource::collection($articles);
    }

    /**
     * @param Article $article
     *
     * @return JsonResource
     */

    public function show(Article $article): JsonResource {

        return ArticleResource::make($article);
    }
}
