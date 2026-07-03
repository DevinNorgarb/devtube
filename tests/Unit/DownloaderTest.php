<?php

declare(strict_types=1);

namespace DevsWebDev\Tests\Unit;

use DevsWebDev\DevTube\Downloader;
use DevsWebDev\DevTube\MediaFile;
use Mockery;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use YoutubeDl\Entity\Video;
use YoutubeDl\Entity\VideoCollection;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class DownloaderTest extends TestCase
{
    protected function tearDown(): void
    {
        foreach (glob(sys_get_temp_dir() . '/devtube_test_*') ?: [] as $dir) {
            if (is_dir($dir)) {
                @rmdir($dir);
            }
        }

        Mockery::close();
        parent::tearDown();
    }

    private function config(): array
    {
        return [
            'download_path' => 'app/devtube',
            'default_format' => 'mp4',
            'output_template' => '%(title)s.%(ext)s',
            'formats' => [
                'mp4' => ['format' => 'mp4'],
                'mp3' => ['extract_audio' => true, 'audio_format' => 'mp3', 'audio_quality' => '0'],
            ],
        ];
    }

    private function tempPath(): string
    {
        return sys_get_temp_dir() . '/devtube_test_' . uniqid();
    }

    public function test_it_maps_videos_to_media_files(): void
    {
        $video = Mockery::mock(Video::class);
        $video->shouldReceive('getTitle')->andReturn('My Song');
        $video->shouldReceive('getFile')->andReturn(new SplFileInfo('/downloads/My Song.mp4'));
        $video->shouldReceive('getError')->andReturn(null);

        $collection = Mockery::mock(VideoCollection::class);
        $collection->shouldReceive('getVideos')->andReturn([$video]);

        $yt = Mockery::mock(YoutubeDl::class);
        $yt->shouldReceive('download')->once()->with(Mockery::type(Options::class))->andReturn($collection);

        $downloader = new Downloader($yt, $this->config());
        $result = $downloader->download('https://youtu.be/abc', 'mp4', $this->tempPath());

        $this->assertCount(1, $result);
        $media = $result->first();
        $this->assertInstanceOf(MediaFile::class, $media);
        $this->assertSame('My Song', $media->title);
        $this->assertSame('/downloads/My Song.mp4', $media->path());
        $this->assertTrue($media->wasSuccessful());
    }

    public function test_it_captures_errors_from_videos(): void
    {
        $video = Mockery::mock(Video::class);
        $video->shouldReceive('getTitle')->andReturn(null);
        $video->shouldReceive('getFile')->andReturn(null);
        $video->shouldReceive('getError')->andReturn('Video unavailable');

        $collection = Mockery::mock(VideoCollection::class);
        $collection->shouldReceive('getVideos')->andReturn([$video]);

        $yt = Mockery::mock(YoutubeDl::class);
        $yt->shouldReceive('download')->andReturn($collection);

        $downloader = new Downloader($yt, $this->config());
        $media = $downloader->download('https://youtu.be/x', 'mp4', $this->tempPath())->first();

        $this->assertFalse($media->wasSuccessful());
        $this->assertSame('Video unavailable', $media->error);
    }

    public function test_it_builds_mp3_options(): void
    {
        $downloader = new Downloader(Mockery::mock(YoutubeDl::class), $this->config());
        $options = $downloader->buildOptions('https://youtu.be/x', 'mp3', '/downloads')->toArray();

        $this->assertTrue($options['extract-audio']);
        $this->assertSame('mp3', $options['audio-format']);
        $this->assertSame('0', $options['audio-quality']);
        $this->assertSame(['https://youtu.be/x'], $options['url']);
        $this->assertSame('/downloads/%(title)s.%(ext)s', $options['output']);
    }

    public function test_it_builds_mp4_options(): void
    {
        $downloader = new Downloader(Mockery::mock(YoutubeDl::class), $this->config());
        $options = $downloader->buildOptions('https://youtu.be/x', 'mp4', '/downloads')->toArray();

        $this->assertSame('mp4', $options['format']);
        $this->assertFalse($options['extract-audio']);
    }
}
