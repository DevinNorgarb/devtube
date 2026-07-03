<?php

declare(strict_types=1);

namespace DevsWebDev\Tests;

use DevsWebDev\DevTube\DevTubeServiceProvider;
use DevsWebDev\DevTube\Facades\DevTube;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [DevTubeServiceProvider::class];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array<string, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return ['DevTube' => DevTube::class];
    }
}
