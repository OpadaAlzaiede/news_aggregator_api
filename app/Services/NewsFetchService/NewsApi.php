<?php

namespace App\Services\NewsFetchService;

use App\Services\NewsFetchService\Abstracts\GenericNewsApi;
use Carbon\Carbon;

class NewsApi extends GenericNewsApi
{
    public function fetchArticles(?string $keyword, ?Carbon $syncFrom)
    {

        $apiKey = config('news_sources.NewsAPI.api_key');
        $endpoint = config('news_sources.NewsAPI.endpoint');

        $this->getArticles(endpoint: $endpoint,
            params: [
                'from' => $syncFrom,
                'sortBy' => 'publishedAt',
                'pageSize' => 10,
                'apiKey' => $apiKey,
                'q' => $keyword,
            ]);
    }

    /**
     *  Get mapping between each service and the article resource
     */
    protected function getMappings(): array
    {

        return [
            'title' => 'title',
            'description' => 'description',
            'content' => 'content',
            'source' => 'source.name',
            'published_at' => 'publishedAt',
        ];
    }

    /**
     * Get the key that represents the articles within the response
     */
    protected function getResponseArticlesKey(): string
    {

        return 'articles';
    }
}
