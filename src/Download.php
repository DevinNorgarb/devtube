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

    public $fileName;

    /**
     * @param [string] $url    [youtube url or id]
     * @param [string] $path   [the path of final destination]
     * @param [string] $format [the media type wished to be downloaded ]
     */
    public function __construct($url, $format = null)
    {
        $this->url =  $url;
        $this->path =   config('devtube.download_path');
        $this->format = $format ?? config('devtube.default_download');
    }



    /**
     * Runs the download process
     * @return [string] [downloaded file path and name]
     */
    public function download()
    {
        if ($this->format == "audio") {
            $file = new Downloader($this->url, true, 'audio');
            $this->fileName = $file->audio;
            return  $this->save($file->audio);
        } else {
            $youtube = new YoutubeDownloader($this->url);
            $youtube->setPath($this->path);

            $youtube->onComplete = function ($filePath, $fileSize, $index, $count) {
                return  $this->save(basename($filePath));
            };

            $youtube->download();
        }
    }

    /**
     * [calls the save method]
     * @param  [type] $filePath [description]
     * @return [type]           [description]
     */
    public function save($filePath)
    {
        $this->savedPath = $this->path."/".$filePath;
        return $this->savedPath;
    }
}
