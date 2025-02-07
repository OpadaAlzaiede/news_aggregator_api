<?php

namespace App\Jobs;

use App\Actions\SyncUserFeedAction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SyncUserFeedJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(SyncUserFeedAction $syncUserFeedAction): void
    {
        $syncUserFeedAction->handle($this->user);
    }
}
