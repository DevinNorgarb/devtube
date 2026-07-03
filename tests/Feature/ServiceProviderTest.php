<?php

declare(strict_types=1);

namespace DevsWebDev\Tests\Feature;

use DevsWebDev\DevTube\Downloader;
use DevsWebDev\DevTube\Facades\DevTube;
use DevsWebDev\Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class ServiceProviderTest extends TestCase
{
    public function test_config_is_merged(): void
    {
        $this->assertSame('yt-dlp', config('devtube.bin_path'));
        $this->assertSame('mp4', config('devtube.default_format'));
    }

    public function test_devtube_resolves_to_downloader(): void
    {
        $this->assertInstanceOf(Downloader::class, $this->app->make('devtube'));
        $this->assertInstanceOf(Downloader::class, $this->app->make(Downloader::class));
    }

    public function test_facade_resolves_to_downloader(): void
    {
        $this->assertInstanceOf(Downloader::class, DevTube::getFacadeRoot());
    }

    public function test_devtube_and_downloader_are_the_same_singleton(): void
    {
        $viaKey = $this->app->make('devtube');

        $this->assertInstanceOf(Downloader::class, $viaKey);
        $this->assertSame($viaKey, $this->app->make('devtube'));
        $this->assertSame($viaKey, $this->app->make(Downloader::class));
    }

    public function test_download_command_is_registered(): void
    {
        $this->assertArrayHasKey('devtube:download', Artisan::all());
    }
}
