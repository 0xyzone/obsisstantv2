<?php

namespace App\Providers;

use App\Services\ObsWebSocketService;
use Illuminate\Support\ServiceProvider;

class ObsWebSocketServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ObsWebSocketService::class, function () {
            return new ObsWebSocketService(
                host: config('obs.host'),
                port: config('obs.port'),
                password: config('obs.password')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
