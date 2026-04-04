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
            \App\Features\Notification\Events\NotificationCreated::class,
            [\App\Features\Notification\Listeners\CreateNotificationRecord::class, 'handle']
        );

        \Illuminate\Support\Facades\Event::listen(
            \App\Features\ActivityLog\Events\ActivityLogged::class,
            [\App\Features\ActivityLog\Listeners\CreateActivityLogRecord::class, 'handle']
        );
    }
}
