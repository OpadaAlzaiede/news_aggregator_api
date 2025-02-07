<?php

use App\Console\Commands\SyncBBCApiArticlesCommand;
use App\Console\Commands\SyncNewsApiArticlesCommand;
use App\Console\Commands\SyncNYTimesApiArticlesCommand;
use App\Console\Commands\SyncUsersFeedCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(SyncBBCApiArticlesCommand::class)->weeklyOn(1, '00:00');
Schedule::command(SyncNewsApiArticlesCommand::class)->weeklyOn(1, '01:00');
Schedule::command(SyncNYTimesApiArticlesCommand::class)->weeklyOn(1, '02:00');
Schedule::command(SyncUsersFeedCommand::class)->weeklyOn(1, '03:00');
