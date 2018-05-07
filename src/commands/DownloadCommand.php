<?php

namespace DevsWebDev\DevTube\Commands;

use Illuminate\Console\Command;
use Masih\YoutubeDownloader\YoutubeDownloader;

class DownloadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $url;

    protected $mediaPath;

    protected $signature = 'devtube:download
    {--url= : Youtube track url}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->mediaPath = storage_path(config('devtube.download_path'));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->url = $this->option('url');

        if (!file_exists($this->mediaPath)) {
            \File::makeDirectory($this->mediaPath);
        }
        $youtube = new YoutubeDownloader($this->url);
        $youtube->setPath($this->mediaPath);

        $youtube->download();
    }
}
