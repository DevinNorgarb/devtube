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
        if ($this->format == "audio") {
            $file = new Downloader($this->url, true, 'audio');
            // return  $this->save($this->path."/".$file->audio);
            return session([$_SERVER['REMOTE_ADDR'] => $this->path."/".$file->audio]);
        } else {
            $youtube = new YoutubeDownloader($this->url);
            $youtube->setPath($this->path);

            $youtube->onComplete = function ($filePath, $fileSize, $index, $count) {
                return  $this->save($filePath);
            };

            $youtube->download();
        }
    }

    public function save($filePath)
    {
        session([$_SERVER['REMOTE_ADDR'] => $this->path."/".basename($filePath)]);
    }
}
