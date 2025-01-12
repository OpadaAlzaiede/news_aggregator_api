<?php

namespace App\Services\NewsFetchService;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\NewsFetchService\Abstracts\GenericNewsApi;

class NYTimesApi extends GenericNewsApi  {

    public function fetchArticles(?string $keyword, Carbon $syncFrom) {

        $endpoint = config('news_sources.NYTimes.endpoint');
        $apiKey = config('news_sources.NYTimes.api_key');
        $timeOut = config('news_sources.time_out');
        $retryTimes = config('news_sources.retry_times');
        $retryWithin = config('news_sources.retry_within');

        try {

            $response = Http::timeout($timeOut)
                            ->retry($retryTimes, $retryWithin)
                            ->get($endpoint, [
                                'api-key' => $apiKey
                            ]);

            if($response->ok()) {

                $responseContent = $response->json();

                $articlesArray = $this->createArticlesArray($responseContent['results'], [
                    'title' => 'title',
                    'description' => 'abstract',
                    'content' => 'abstract',
                    'source' => 'source',
                    'published_at' => 'published_date'
                ]);

                $this->storeArticles($articlesArray);

            }

        } catch(\Throwable $e) {

            Log::error('error fetching articles from NYTimes: ' . $e->getMessage());
        }
    }
}
