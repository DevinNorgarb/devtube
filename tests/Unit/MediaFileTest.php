<?php

declare(strict_types=1);

namespace DevsWebDev\Tests\Unit;

use DevsWebDev\DevTube\MediaFile;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class MediaFileTest extends TestCase
{
    public function test_successful_media_exposes_path(): void
    {
        $media = new MediaFile('My Song', new SplFileInfo('/downloads/My Song.mp4'), null);

        $this->assertSame('My Song', $media->title);
        $this->assertSame('/downloads/My Song.mp4', $media->path());
        $this->assertTrue($media->wasSuccessful());
    }

    public function test_errored_media_is_not_successful(): void
    {
        $media = new MediaFile(null, null, 'Video unavailable');

        $this->assertNull($media->path());
        $this->assertSame('Video unavailable', $media->error);
        $this->assertFalse($media->wasSuccessful());
    }

    public function test_media_without_file_is_not_successful(): void
    {
        $media = new MediaFile('Title only', null, null);

        $this->assertFalse($media->wasSuccessful());
    }
}
