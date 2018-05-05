<?php

namespace DevsWebDev\DevTube;

use Masih\YoutubeDownloader\YoutubeDownloader;

class Download
{
    protected $youtube;

    protected $path;

    public function __construct($url, $path)
    {
        // dd($url);
        $this->youtube = new YoutubeDownloader($url);

        $this->path = $path ?? storage_path("/");

        $this->youtube->setPath($this->path);
    }

    public function download()
    {
        $this->youtube->onProgress = function ($downloadedBytes, $fileSize, $index, $count) {
            if ($count > 1) {
                // echo '[' . $index . ' of ' . $count . ' videos] ';
            }
            if ($fileSize > 0) {
                // echo "\r" . 'Downloaded ' . $downloadedBytes . ' of ' . $fileSize . ' bytes [%' . number_format($downloadedBytes * 100 / $fileSize, 2) . '].';
            } else {
                // echo "\r" . 'Downloading...';
            }
        };

        $this->youtube->onFinalized = function ($filePath, $fileSize, $index, $count) {
            if ($count > 1) {
                // echo '[' . $index . ' of ' . $count . ' videos] ';
            }
            // echo $filePath . ' Finalized' . PHP_EOL;
        };

        $this->youtube->onComplete = function ($filePath, $fileSize, $index, $count) {
            if ($count > 1) {
                // echo '[' . $index . ' of ' . $count . ' videos] ';
            }
            // echo 'Downloading of ' . $fileSize . ' bytes has been completed. It is saved in ' . $filePath . PHP_EOL;
        };


        return response()->streamDownload(function () {
            $this->youtube->download();
        }, 'laravel-readme.mp4');
        // return view('welcome') ;
    }
}
