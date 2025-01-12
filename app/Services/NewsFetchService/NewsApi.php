<?php

namespace App\Services\NewsFetchService;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\NewsFetchService\Abstracts\GenericNewsApi;

class NewsApi extends GenericNewsApi  {

    public function fetchArticles(?string $keyword, Carbon $syncFrom) {

        $apiKey = config('news_sources.NewsAPI.api_key');
        $endpoint = config('news_sources.NewsAPI.endpoint');
        $timeOut = config('news_sources.time_out');
        $retryTimes = config('news_sources.retry_times');
        $retryWithin = config('news_sources.retry_within');

        try {

            $response = Http::timeout($timeOut)
                            ->retry($retryTimes, $retryWithin)
                            ->get($endpoint, [
                                'from' => $syncFrom,
                                'sortBy' => 'publishedAt',
                                'pageSize' => 10,
                                'apiKey' => $apiKey,
                                'q' => $keyword
                            ]);

            if($response->ok()) {
                $responseContent = $response->json();

                $articlesArray = $this->createArticlesArray($responseContent['articles'], [
                    'title' => 'title',
                    'description' => 'description',
                    'content' => 'content',
                    'source' => 'source.name',
                    'published_at' => 'publishedAt'
                ]);

                $this->storeArticles($articlesArray);
            }

        } catch(\Throwable $e) {

            Log::error('error fetching articles from NewApi: ' . $e->getMessage());
        }
    }
}
