<?php

namespace App\Console\Commands;

use App\Enums\HasPreferencesEnum;
use App\Jobs\SyncUserFeedJob;
use App\Models\User;
use Illuminate\Console\Command;

class SyncUsersFeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-users-feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command syncs all users feed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()
            ->where('has_preferences', HasPreferencesEnum::YES->value)
            ->chunk(100, function ($users) {
                foreach ($users as $user) {

                    SyncUserFeedJob::dispatch($user);
                }
            });
    }
}
