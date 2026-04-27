<?php

namespace App\Providers;

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
        \Illuminate\Support\Facades\Event::listen(
            \App\Events\NotificationCreated::class,
            [\App\Listeners\CreateNotificationRecord::class, 'handle']
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Events\ActivityLogged::class,
            [\App\Listeners\CreateActivityLogRecord::class, 'handle']
        );
    }
}
