<?php

namespace DevsWebDev\DevTube;

use DevsWebDev\DevTube\Commands\DownloadCommand;
use Illuminate\Support\ServiceProvider;

class DevTubeServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->app->bind('devtube', function ($app) {
            return new DevTube;
        });

        $this->app->bind('command.devtube:download', DownloadCommand::class);

        $this->commands([
            'command.devtube:download',
        ]);
    }

    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
        __DIR__.'/../config/devtube.php' => config_path('devtube.php')
      ], 'config');
    }
}
