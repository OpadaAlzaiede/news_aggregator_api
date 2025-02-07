<?php

namespace App\Console\Commands;

use App\Actions\GetMostPopularPreferenceAction;
use App\Services\NewsFetchService\Abstracts\NewsFetchContract;
use Illuminate\Console\Command;

class SyncNYTimesApiArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:NYTimes-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sync articles from NYTimes source.';

    /**
     * Execute the console command.
     */
    public function handle(NewsFetchContract $newsFetchContract, GetMostPopularPreferenceAction $getMostPopularPreferenceAction)
    {
        $syncFrom = now()->subDays(3);

        $newsFetchContract->fetchArticles($getMostPopularPreferenceAction->handle($syncFrom), $syncFrom);
    }
}
