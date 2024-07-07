<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// Schedule::job(new FetchAndStoreUserRecordsJob)->everyMinute();
Schedule::command('fetch:users')->everyMinute();
Schedule::command('app:daily-records')->everyMinute();
// Schedule::command('fetch:users')->hourly();
// Schedule::command('app:daily-records')->dailyAt('00:00');