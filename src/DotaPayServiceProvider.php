<?php

namespace DotaPay\LaravelSdk;

use Illuminate\Support\ServiceProvider;

class DotaPayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/dotapay.php', 'dotapay');

        $this->app->singleton(DotapayManager::class, function ($app) {
            return new DotapayManager($app['config']->get('dotapay', []));
        });

        $this->app->alias(DotapayManager::class, 'dotapay');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/dotapay.php' => config_path('dotapay.php'),
        ], 'dotapay-config');
    }
}
