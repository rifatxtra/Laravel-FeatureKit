<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FeatureRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadWebRoutes();
        $this->loadApiRoutes();
    }

    protected function loadWebRoutes(): void
    {
        foreach (glob(app_path('Features/*/web.php')) as $file) {
            Route::middleware('web')->group($file);
        }
    }

    protected function loadApiRoutes(): void
    {
        foreach (glob(app_path('Features/*/api.php')) as $file) {
            Route::prefix('api')
                ->middleware('api')
                ->group($file);
        }
    }
}
