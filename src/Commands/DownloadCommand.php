<?php

declare(strict_types=1);

namespace DevsWebDev\DevTube\Commands;

use DevsWebDev\DevTube\Downloader;
use DevsWebDev\DevTube\MediaFile;
use Illuminate\Console\Command;

class DownloadCommand extends Command
{
    protected $signature = 'devtube:download
        {url : The video or playlist URL}
        {--format= : Output format key (e.g. mp4, mp3)}
        {--path= : Absolute download path override}';

    protected $description = 'Download a video or playlist via yt-dlp';

    public function handle(Downloader $downloader): int
    {
        $results = $downloader->download(
            (string) $this->argument('url'),
            $this->option('format'),
            $this->option('path'),
        );

        $rows = $results
            ->map(static fn (MediaFile $media): array => [
                $media->title ?? '—',
                $media->path() ?? '—',
                $media->wasSuccessful() ? 'OK' : ($media->error ?? 'FAILED'),
            ])
            ->all();

        $this->table(['Title', 'File', 'Status'], $rows);

        $failed = $results->contains(static fn (MediaFile $media): bool => ! $media->wasSuccessful());

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
