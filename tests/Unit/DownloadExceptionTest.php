<?php

declare(strict_types=1);

namespace DevsWebDev\Tests\Unit;

use DevsWebDev\DevTube\Exceptions\DownloadException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class DownloadExceptionTest extends TestCase
{
    public function test_it_is_a_runtime_exception(): void
    {
        $exception = new DownloadException('boom');

        $this->assertInstanceOf(RuntimeException::class, $exception);
        $this->assertSame('boom', $exception->getMessage());
    }
}
