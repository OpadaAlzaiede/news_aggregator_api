<?php

namespace App\Services\NewsFetchService;

use App\Services\NewsFetchService\Abstracts\GenericNewsApi;
use Carbon\Carbon;

class BBCApi extends GenericNewsApi
{
    public function fetchArticles(?string $keyword, ?Carbon $syncFrom)
    {

        $endpoint = config('news_sources.BBC.endpoint');

        $this->getArticles(endpoint: $endpoint, params: ['lang' => 'english']);
    }

    /**
     *  Get mapping between each service and the article resource
     */
    protected function getMappings(): array
    {

        return [
            'title' => 'title',
            'description' => 'summary',
            'content' => 'summary',
        ];
    }

    /**
     * Get the key that represents the articles within the response
     */
    protected function getResponseArticlesKey(): string
    {

        return 'Latest';
    }
}
