<?php

namespace App\Services\NewsFetchService;

use Carbon\Carbon;
use App\Services\NewsFetchService\Abstracts\GenericNewsApi;

class NewsApi extends GenericNewsApi  {

    /**
     * @param ?string $keyword
     * @param ?Carbon $syncFrom
     */
    public function fetchArticles(?string $keyword, ?Carbon $syncFrom) {

        $apiKey = config('news_sources.NewsAPI.api_key');

        $this->getArticles(endpoint: config('news_sources.NewsAPI.api_key'),
            params: [
                'from' => $syncFrom,
                'sortBy' => 'publishedAt',
                'pageSize' => 10,
                'apiKey' => $apiKey,
                'q' => $keyword
            ]);
        }

    /**
     *  Get mapping between each service and the article resource
     *
     * @return array
     */
    protected function getMappings(): array {

        return [
            'title' => 'title',
            'description' => 'description',
            'content' => 'content',
            'source' => 'source.name',
            'published_at' => 'publishedAt'
        ];
    }

    /**
     * Get the key that represents the articles within the response
     *
     * @return string
     */
    protected function getResponseArticlesKey(): string {

        return 'articles';
    }
}
