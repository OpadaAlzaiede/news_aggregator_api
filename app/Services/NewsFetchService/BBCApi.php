<?php

namespace App\Services\NewsFetchService;

use Carbon\Carbon;
use App\Services\NewsFetchService\Abstracts\GenericNewsApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class BBCApi extends GenericNewsApi {

    public function fetchArticles(?string $keyword, Carbon $syncFrom) {

        $endpoint = config('news_sources.BBC.endpoint');
        $timeOut = config('news_sources.time_out');
        $retryTimes = config('news_sources.retry_times');
        $retryWithin = config('news_sources.retry_within');

        try {

            $response = Http::timeout($timeOut)
                            ->retry($retryTimes, $retryWithin)
                            ->get($endpoint, [
                                'lang' => 'english'
                            ]);

            if($response->ok()) {

                $responseContent = $response->json();

                $articlesArray = $this->createArticlesArray($responseContent['Latest'], [
                    'title' => 'title',
                    'description' => 'summary',
                    'content' => 'summary',
                ]);

                $this->storeArticles($articlesArray);
            }

        } catch(\Throwable $e) {

            Log::error('error fetching articles from BBCApi: ' . $e->getMessage());
        }
    }

}
