<?php

return [

    'time_out' => 10,
    'retry_times' => 2,
    'retry_within' => 100,
    'NewsAPI' => [
        'endpoint' => 'https://newsapi.org/v2/everything',
        'api_key' => env('NEWS_API_KEY')
    ],
    'BBC' => [
        'endpoint' => 'https://bbc-api.vercel.app/latest'
    ],
    'NYTimes' => [
        'endpoint' => 'https://api.nytimes.com/svc/news/v3/content/all/all.json',
        'api_key' => env('NYT_API_KEY')
    ]
];
