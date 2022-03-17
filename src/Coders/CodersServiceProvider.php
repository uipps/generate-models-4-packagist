<?php

namespace Uipps\GenerateModels4Packagist\Coders;

use Illuminate\Support\ServiceProvider;
use Uipps\GenerateModels4Packagist\Coders\Commands\CodeModelsCommand;

class CodersServiceProvider extends ServiceProvider
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
                CodeModelsCommand::class,
            ]);
        }
    }
}
