<?php

namespace DevsWebDev\DevTube;

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
