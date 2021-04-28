<?php

namespace Tochka\ModelHistory;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

/**
 * Integration
 * @codeCoverageIgnore
 */
class ModelHistoryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/model-history.php' => App::configPath('model-history.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => App::databasePath('migrations'),
        ], 'migrations');
    }
}
