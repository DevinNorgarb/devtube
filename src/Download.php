<?php

namespace DevsWebDev\DevTube;

use DevsWebDev\DevTube\Downloader;
use Illuminate\Support\Facades\Storage;
use Masih\YoutubeDownloader\YoutubeDownloader;

class Download
{
    /**
     * [public youtube class object]
     * @var [object]
     */
    public $youtube;

    /**
     * [public download filesystem path]
     * @var [string]
     */
    public $path;

    /**
     * [public saved path dir]
     * @var [string]
     */
    public $savedPath;

    /**
     * [public youtube video url]
     * @var [string]
     */
    public $url;

    /**
     * [public whether video or audio is to be downloaded]
     * @var [string]
     */
    public $format;

    /**
     * [downloaded filename]
     * @var [string]
     */
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
     * Begin the download process
     * @return [string] [downloaded file path and name]
     */
    public function download()
    {
        if ($this->format == "audio") {
            $this->downloadAudio();
        } else {
            $this->downloadVideo();
        }
    }

    public function downloadAudio()
    {
        try {
            $file = new Downloader($this->url, true, 'audio');
            $this->fileName = $file->audio;
            return  $this->save($file->audio);
        } catch (\Exception $e) {
            $youtube = new YoutubeDownloader($this->url);
            $youtube->setPath($this->path);

            $youtube->onComplete = function ($filePath, $fileSize, $index, $count) {
                $this->save(basename($filePath));
                $this->convert(basename($filePath));
            };

            $youtube->download();
        }
    }

    public function downloadVideo()
    {
        $youtube = new YoutubeDownloader($this->url);
        $youtube->setPath($this->path);

        $youtube->onComplete = function ($filePath, $fileSize, $index, $count) {
            return  $this->save(basename($filePath));
        };

        $youtube->download();
    }

    public function convert($filePath)
    {
        exec("ffmpeg -i ".$this->savedPath." -vn -ar 44100 -ac 2 -ab 192000 -f mp3 ".$this->savedPath.".mp3");
        $this->savedPath = $this->path."/".$filePath.".mp3";
        return $this->savedPath;
    }

    /**
     * [calls the save method]
     * @param  [type] $filePath [downloaded files path]
     */
    public function save($filePath)
    {
        $this->savedPath = $this->path."/".$filePath;
        return $this->savedPath;
    }
}
