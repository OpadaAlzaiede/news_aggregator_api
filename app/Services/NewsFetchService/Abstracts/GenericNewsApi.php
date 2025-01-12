<?php

namespace App\Services\NewsFetchService\Abstracts;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

abstract class GenericNewsApi implements NewsFetchContract {


    /**
     * @param $endpoint
     * @param array $params
     */
    protected function getArticles(string $endpoint, array $params) {

        $timeOut = config('news_sources.time_out');
        $retryTimes = config('news_sources.retry_times');
        $retryWithin = config('news_sources.retry_within');

        try {

            $response = Http::timeout($timeOut)
                            ->retry($retryTimes, $retryWithin)
                            ->get($endpoint, $params);

            if($response->ok()) {

                $this->createArticles(articles: $response->json($this->getResponseArticlesKey()));

                Log::channel('sync_success')->info("syncing news from ".$endpoint);
            }else {

                Log::channel('sync_failure')->error("syncing news from ".$endpoint, ['response' => $response]);
            }

        } catch(\Throwable $e) {

            Log::channel('sync_failure')->error("syncing news from ".$endpoint, ['exception' => $e->getMessage()]);
        }
    }

    /**
     * @param array $articles
     * @param array $mappings
     *
     */
    public function createArticles(array $articles) {

        $articlesArray = [];

        foreach ($articles as $article) {

            $data = [];

            foreach ($this->getMappings() as $key => $value) {

                $data[$key] = $this->getValueFromNestedKey(array: $article, keys: $value);
            }

            $data['slug'] = Str::slug($data['title']);

            $articlesArray[] = $data;
        }

        $this->storeArticles(articlesArray: $articlesArray);
    }

    /**
     * @param array $articlesArray
     */
    public function storeArticles(array $articlesArray) {

        Article::insert($articlesArray);
    }

    /**
     * @param array $array
     * @param string $keys
     *
     * @return $array
     */
    private function getValueFromNestedKey(array $array, string $keys) {

        $keys = explode('.', $keys);

        foreach ($keys as $key) {

            if (isset($array[$key])) {

                $array = $array[$key];
            } else {

                return null;
            }
        }

        return $array;
    }

    /**
     *  Get mapping between each service and the article resource
     *
     * @return array
     */
    abstract protected function getMappings(): array;

    /**
     * Get the key that represents the articles within the response
     *
     * @return string
     */
    abstract protected function getResponseArticlesKey(): string;
}
