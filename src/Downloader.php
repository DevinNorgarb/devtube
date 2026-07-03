<?php

declare(strict_types=1);

namespace DevsWebDev\DevTube;

use DevsWebDev\DevTube\Exceptions\DownloadException;
use Illuminate\Support\Collection;
use Throwable;
use YoutubeDl\Entity\Video;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class Downloader
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        private readonly YoutubeDl $yt,
        private readonly array $config,
    ) {
    }

    /**
     * @return Collection<int, MediaFile>
     */
    public function download(string $url, ?string $format = null, ?string $path = null): Collection
    {
        $format = $format ?: (string) ($this->config['default_format'] ?? 'mp4');
        $path = $path ?: $this->defaultPath();

        $this->ensureDirectory($path);

        $options = $this->buildOptions($url, $format, $path);

        try {
            $collection = $this->yt->download($options);
        } catch (Throwable $e) {
            throw new DownloadException("yt-dlp download failed: {$e->getMessage()}", 0, $e);
        }

        return Collection::make($collection->getVideos())
            ->map(static function (Video $video): MediaFile {
                $error = $video->getError();

                return new MediaFile(
                    title: $video->getTitle(),
                    // Video::getFile() is non-nullable in youtube-dl-php v2 and returns null
                    // for errored videos (no 'file' key), so only read it on success.
                    file: $error === null ? $video->getFile() : null,
                    error: $error,
                );
            })
            ->values();
    }

    public function buildOptions(string $url, string $format, string $path): Options
    {
        $options = Options::create()
            ->downloadPath($path)
            ->output((string) ($this->config['output_template'] ?? '%(title)s.%(ext)s'))
            ->url($url);

        /** @var array<string, mixed> $formatOptions */
        $formatOptions = $this->config['formats'][$format] ?? [];

        if (! empty($formatOptions['extract_audio'])) {
            $options = $options->extractAudio(true);
        }

        if (! empty($formatOptions['audio_format'])) {
            $options = $options->audioFormat((string) $formatOptions['audio_format']);
        }

        if (isset($formatOptions['audio_quality'])) {
            $options = $options->audioQuality((string) $formatOptions['audio_quality']);
        }

        if (! empty($formatOptions['format'])) {
            $options = $options->format((string) $formatOptions['format']);
        }

        return $options;
    }

    private function defaultPath(): string
    {
        $downloadPath = (string) ($this->config['download_path'] ?? 'app/devtube');

        return function_exists('storage_path') ? storage_path($downloadPath) : $downloadPath;
    }

    private function ensureDirectory(string $path): void
    {
        if (! is_dir($path) && ! @mkdir($path, 0775, true) && ! is_dir($path)) {
            throw new DownloadException("Unable to create download directory: {$path}");
        }
    }
}
