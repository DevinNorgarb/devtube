<?php

declare(strict_types=1);

namespace DevsWebDev\DevTube;

use DevsWebDev\DevTube\Commands\DownloadCommand;
use Illuminate\Support\ServiceProvider;
use YoutubeDl\YoutubeDl;

class DevTubeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/devtube.php', 'devtube');

        $this->app->singleton('devtube', function ($app): Downloader {
            /** @var array<string, mixed> $config */
            $config = $app['config']->get('devtube');

            $yt = new YoutubeDl();

            if (! empty($config['bin_path'])) {
                $yt->setBinPath((string) $config['bin_path']);
            }

            return new Downloader($yt, $config);
        });

        $this->app->alias('devtube', Downloader::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/devtube.php' => config_path('devtube.php'),
            ], 'config');

            $this->commands([
                DownloadCommand::class,
            ]);
        }
    }
}
