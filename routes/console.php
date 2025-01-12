<?php

use App\Console\Commands\SyncBBCApiArticlesCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command(SyncBBCApiArticlesCommand::class)->weeklyOn(1, '00:00');
Schedule::command(SyncBBCApiArticlesCommand::class)->weeklyOn(1, '01:00');
Schedule::command(SyncBBCApiArticlesCommand::class)->weeklyOn(1, '02:00');
