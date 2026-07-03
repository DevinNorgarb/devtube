<?php

declare(strict_types=1);

namespace DevsWebDev\Tests\Feature;

use DevsWebDev\DevTube\Downloader;
use DevsWebDev\DevTube\MediaFile;
use DevsWebDev\Tests\TestCase;
use Illuminate\Support\Collection;
use Mockery;
use SplFileInfo;

class DownloadCommandTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * @param Collection<int, MediaFile> $results
     */
    private function fakeDownloaderReturning(Collection $results): void
    {
        $mock = Mockery::mock(Downloader::class);
        $mock->shouldReceive('download')->andReturn($results);

        $this->app->instance('devtube', $mock);
    }

    public function test_it_exits_success_when_all_items_succeed(): void
    {
        $this->fakeDownloaderReturning(collect([
            new MediaFile('Song', new SplFileInfo('/tmp/Song.mp3'), null),
        ]));

        $this->artisan('devtube:download', ['url' => 'https://youtu.be/x', '--format' => 'mp3'])
            ->assertExitCode(0);
    }

    public function test_it_exits_failure_when_any_item_fails(): void
    {
        $this->fakeDownloaderReturning(collect([
            new MediaFile('Song', new SplFileInfo('/tmp/Song.mp4'), null),
            new MediaFile(null, null, 'Video unavailable'),
        ]));

        $this->artisan('devtube:download', ['url' => 'https://youtu.be/x'])
            ->assertExitCode(1);
    }
}
