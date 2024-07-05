<?php

namespace App\Providers;

use App\Events\UserRecordDeleted;
use App\Listeners\UpdateDailyRecord;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRecordDeleted::class => [
            UpdateDailyRecord::class,
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
