<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Pagination\Paginator;

class ArticlesService
{
    public function getArticles(
        int $perPage,
        int $page,
        ?string $keyword = null,
        ?string $publishedAt = null,
        ?string $category = null,
        ?string $source = null,
    ): Paginator {

        $query = Article::query()
            ->select(['title', 'slug', 'description', 'category', 'author', 'source', 'published_at']);

        $query->when($keyword, function ($query, $keyword) {
            $query->where(function ($query) use ($keyword) {
                $query->whereFullText(
                    ['title', 'description', 'content'],
                    $keyword,
                    ['mode' => 'boolean']
                );
            });
        });

        $query->when(! $keyword, function ($query) use ($publishedAt, $category, $source) {
            $query->when($publishedAt, function ($query, $publishedAt) {
                $query->whereDate('published_at', $publishedAt);
            });

            $query->when($category, function ($query, $category) {
                $query->where('category', 'LIKE', $category.'%');
            });

            $query->when($source, function ($query, $source) {
                $query->where('source', 'LIKE', $source.'%');
            });

            $query->latest('id');
        });

        return $query->simplePaginate($perPage, ['*'], 'page', $page);
    }
}
