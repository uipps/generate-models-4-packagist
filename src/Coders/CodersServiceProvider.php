<?php

namespace Uipps\Coders;

use Illuminate\Support\ServiceProvider;
use Uipps\Coders\Console\CodeModelsCommand;

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
