<?php

namespace DevsWebDev\DevTube\Http;

use App\Http\Controllers\Controller;
use Masih\YoutubeDownloader\YoutubeDownloader;

class DownloadController extends Controller
{
    public $youtube;

    public function __construct($id = null, $location = null)
    {
        $this->youtube = new YoutubeDownloader($id ?? "WO5X9ZUzqXM");
        $this->youtube->setPath(storage_path($location ?? "/")); // without trailing slash
    }

    public function download()
    {
        $youtube = $this->youtube;
        $youtube->onProgress = function ($downloadedBytes, $fileSize, $index, $count) {
            if ($count > 1) {
                echo '[' . $index . ' of ' . $count . ' videos] ';
            }
            if ($fileSize > 0) {
                echo "\r" . 'Downloaded ' . $downloadedBytes . ' of ' . $fileSize . ' bytes [%' . number_format($downloadedBytes * 100 / $fileSize, 2) . '].';
            } else {
                echo "\r" . 'Downloading...';
            } // File size is unknown, so just keep downloading
        };

        $youtube->onFinalized = function ($filePath, $fileSize, $index, $count) {
            if ($count > 1) {
                echo '[' . $index . ' of ' . $count . ' videos] ';
            }
            echo $filePath . ' Finalized' . PHP_EOL;
        };

        $youtube->onComplete = function ($filePath, $fileSize, $index, $count) {
            if ($count > 1) {
                echo '[' . $index . ' of ' . $count . ' videos] ';
            }
            echo 'Downloading of ' . $fileSize . ' bytes has been completed. It is saved in ' . $filePath . PHP_EOL;
        };

        $youtube->download();
    }
}
