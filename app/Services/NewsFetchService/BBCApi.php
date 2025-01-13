<?php

namespace App\Services\NewsFetchService;

use Carbon\Carbon;
use App\Services\NewsFetchService\Abstracts\GenericNewsApi;

class BBCApi extends GenericNewsApi {

    /**
     * @param ?string $keyword
     * @param ?Carbon $syncFrom
     */
    public function fetchArticles(?string $keyword, ?Carbon $syncFrom) {

        $endpoint = config('news_sources.BBC.endpoint');

        $this->getArticles(endpoint: $endpoint, params: ['lang' => 'english']);
    }

    /**
     *  Get mapping between each service and the article resource
     *
     * @return array
     */
    protected function getMappings(): array {

        return [
            'title' => 'title',
            'description' => 'summary',
            'content' => 'summary',
        ];
    }

    /**
     * Get the key that represents the articles within the response
     *
     * @return string
     */
    protected function getResponseArticlesKey(): string {

        return 'Latest';
    }
}
