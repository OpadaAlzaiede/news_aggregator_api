<?php

namespace App\Services\NewsFetchService;

use Carbon\Carbon;
use App\Services\NewsFetchService\Abstracts\GenericNewsApi;

class NYTimesApi extends GenericNewsApi  {

    /**
     * @param ?string $keyword
     * @param ?Carbon $syncFrom
     */
    public function fetchArticles(?string $keyword, ?Carbon $syncFrom) {

        $apiKey = config('news_sources.NYTimes.api_key');

        $this->getArticles(endpoint: config('news_sources.NYTimes.endpoint'),
        params: [
            'api-key' => $apiKey
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
            'description' => 'abstract',
            'content' => 'abstract',
            'source' => 'source',
            'published_at' => 'published_date'
        ];
    }

    /**
     * Get the key that represents the articles within the response
     *
     * @return string
     */
    protected function getResponseArticlesKey(): string {

        return 'results';
    }
}
