<?php

declare(strict_types=1);

namespace DevsWebDev\DevTube\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection download(string $url, ?string $format = null, ?string $path = null)
 *
 * @see \DevsWebDev\DevTube\Downloader
 */
class DevTube extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'devtube';
    }
}
