<?php

namespace Uipps\GenerateModels4Packagist\Providers;

use Illuminate\Support\ServiceProvider;
use Uipps\GenerateModels4Packagist\Commands\GenerateModelsCommand;

class GenerateModelsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateModelsCommand::class,
            ]);
        }
    }
}
