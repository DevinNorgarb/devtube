<?php

namespace DevsWebDev\DevTube;

use DevsWebDev\DevTube\Downloader;
use Illuminate\Support\Facades\Storage;
use Masih\YoutubeDownloader\YoutubeDownloader;

class Download
{
    public $youtube;

    public $path;

    public $savedPath;

    public $url;

    public $format;

    public function __construct($url, $path = null, $format = null)
    {
        $this->url =  $url;
        $this->path =  $path ?? config('devtube.download_path');
        $this->format = $format ?? config('devtube.default_download');
    }

    public function download()
    {

      // $this->decide();

        try {
            // Instantly download a YouTube video (using the default settings).
            new Downloader($this->url, true);

            // Instantly download a YouTube video as MP3 (using the default settings).
            new Downloader($this->url, true, 'audio');
        } catch (Exception $e) {
            die($e->getMessage());
        }

        dd("here");

        $youtube = new YoutubeDownloader($this->url);
        $youtube->setPath($this->path);

        $youtube->onComplete = function ($filePath, $fileSize, $index, $count) {
            return  $this->save($filePath);
        };

        $youtube->download();
    }

    public function save($filePath)
    {
        session([$_SERVER['REMOTE_ADDR'] => $this->path."/".basename($filePath)]);
    }
}
