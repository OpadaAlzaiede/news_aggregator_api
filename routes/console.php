<?php

use App\Console\Commands\SyncBBCApiArticlesCommand;
use App\Console\Commands\SyncNewsApiArticlesCommand;
use App\Console\Commands\SyncNYTimesApiArticlesCommand;
use Illuminate\Support\Facades\Schedule;


Schedule::command(SyncBBCApiArticlesCommand::class)->weeklyOn(1, '00:00');
Schedule::command(SyncNewsApiArticlesCommand::class)->weeklyOn(1, '01:00');
Schedule::command(SyncNYTimesApiArticlesCommand::class)->weeklyOn(1, '02:00');
