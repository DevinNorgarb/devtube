<?php

namespace DevsWebDev\DevTube;

use Illuminate\Support\Facades\Storage;
use Masih\YoutubeDownloader\YoutubeDownloader;

class Download
{
    public $youtube;

    public $path;

    public $savedPath;


    public function __construct($url, $path = null)
    {
        $this->url =  $url;
        $this->path =  $path ?? storage_path(config('devtube.download_path'));
    }

    public function download()
    {
        $youtube = new YoutubeDownloader($this->url);
        $youtube->setPath($this->path);

        $youtube->onComplete = function ($filePath, $fileSize, $index, $count) {
            return  $this->save($filePath);
        };

        $youtube->download();
    }

    public function save($filePath)
    {
        $file = basename($filePath);
        session([$_SERVER['REMOTE_ADDR'] => 'app/public/music/'.$file]);
    }
}
