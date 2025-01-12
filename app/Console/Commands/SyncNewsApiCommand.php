<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\NewsSource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\NewsFetchService\BBCApi;
use App\Services\NewsFetchService\NewsApi;
use App\Services\NewsFetchService\NYTimesApi;
use App\Actions\GetMostPopularPreferenceAction;

class SyncNewsApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-news-api-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync news from NewsAPI.';

    /**
     * Execute the console command.
     */
    public function handle(GetMostPopularPreferenceAction $getMostPopularPreferenceAction)
    {
        $syncFrom = now()->subDays(3);

        (new NewsApi)->fetchArticles($getMostPopularPreferenceAction->handle($syncFrom), $syncFrom);
        (new BBCApi)->fetchArticles($getMostPopularPreferenceAction->handle($syncFrom), $syncFrom);
        (new NYTimesApi)->fetchArticles($getMostPopularPreferenceAction->handle($syncFrom), $syncFrom);
    }
}
