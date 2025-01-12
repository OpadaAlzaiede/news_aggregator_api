<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\GetMostPopularPreferenceAction;
use App\Services\NewsFetchService\Abstracts\NewsFetchContract;

class SyncNewsApiArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:news-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command sync articles from NewsApi source.';

    /**
     * Execute the console command.
     */
    public function handle(NewsFetchContract $newsFetchContract, GetMostPopularPreferenceAction $getMostPopularPreferenceAction)
    {
        $syncFrom = now()->subDays(3);

        $newsFetchContract->fetchArticles($getMostPopularPreferenceAction->handle($syncFrom), $syncFrom);
    }
}
