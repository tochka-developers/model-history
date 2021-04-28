<?php

namespace Tochka\ModelHistory;

use Illuminate\Support\ServiceProvider;

/**
 * Integration
 */
class ModelHistoryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/model-history.php' => config_path('model-history.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');
    }
}
