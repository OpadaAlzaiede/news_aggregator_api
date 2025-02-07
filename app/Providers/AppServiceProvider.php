<?php

namespace App\Providers;

use App\Console\Commands\SyncBBCApiArticlesCommand;
use App\Console\Commands\SyncNewsApiArticlesCommand;
use App\Console\Commands\SyncNYTimesApiArticlesCommand;
use App\Services\NewsFetchService\Abstracts\NewsFetchContract;
use App\Services\NewsFetchService\BBCApi;
use App\Services\NewsFetchService\NewsApi;
use App\Services\NewsFetchService\NYTimesApi;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->when(SyncBBCApiArticlesCommand::class)
            ->needs(NewsFetchContract::class)
            ->give(BBCApi::class);

        $this->app->when(SyncNewsApiArticlesCommand::class)
            ->needs(NewsFetchContract::class)
            ->give(NewsApi::class);

        $this->app->when(SyncNYTimesApiArticlesCommand::class)
            ->needs(NewsFetchContract::class)
            ->give(NYTimesApi::class);
    }
}
