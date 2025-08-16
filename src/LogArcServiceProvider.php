<?php

namespace Dipesh79\LogArcLaravel;

use Dipesh79\LogArcLaravel\Service\LogArcService;
use Illuminate\Support\ServiceProvider;

class LogArcServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('logarc', function () {
            return new LogArcService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config if needed
        $this->publishes([
            __DIR__ . '/config/logarc.php' => config_path('logarc.php'),
        ], 'config');
    }
}
