<?php

namespace App\Services\NewsFetchService;

use App\Services\NewsFetchService\Abstracts\GenericNewsApi;
use Carbon\Carbon;

class NYTimesApi extends GenericNewsApi
{
    public function fetchArticles(?string $keyword, ?Carbon $syncFrom)
    {

        $apiKey = config('news_sources.NYTimes.api_key');
        $endpoint = config('news_sources.NYTimes.endpoint');

        $this->getArticles(endpoint: $endpoint,
            params: [
                'api-key' => $apiKey,
            ]);
    }

    /**
     *  Get mapping between each service and the article resource
     */
    protected function getMappings(): array
    {

        return [
            'title' => 'title',
            'description' => 'abstract',
            'content' => 'abstract',
            'source' => 'source',
            'published_at' => 'published_date',
        ];
    }

    /**
     * Get the key that represents the articles within the response
     */
    protected function getResponseArticlesKey(): string
    {

        return 'results';
    }
}
