<?php

namespace App\Services\NewsFetchService\Abstracts;

use Carbon\Carbon;
use App\Models\Article;

abstract class GenericNewsApi implements NewsFetchContract {

    public function storeArticles(array $articlesArray) {

        Article::insert($articlesArray);
    }

    public function createArticlesArray($articles, $mapping) {

        $articlesArray = [];

        foreach ($articles as $article) {
            $data = [];
            foreach ($mapping as $key => $value) {
                $data[$key] = $article[$value];
            }
            $articlesArray[] = $data;
        }

        return $articlesArray;
    }

    abstract public function fetchArticles(?string $keyword, Carbon $syncFrom);
}
