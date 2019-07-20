<?php

namespace DevsWebDev\DevTube;

use DevsWebDev\DevTube\Downloader;
use DevsWebDev\DevTube\MediaDownload;

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
    public function __construct($url, $format = "mp3", $path = null)
    {
        $this->url =  $url;
        $this->path =  $path ?: storage_path(config('devtube.download_path'));
        $this->format = $format ?: config('devtube.default_download');
    }



    /**
     * Begin the download process
     * @return [string] [downloaded file path and name]
     */
    public function download()
    {
        $media_info = (new MediaDownload(
            $this
        ))->getPlaylistIds();


        $arr = [];
        foreach ($media_info as $key => $info) {
            $res = MediaDownload::download($info, $this->format);
            $arr[] = $res;
        }

        return collect($arr);
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
        $this->savedPath = $this->path . "/" . $filePath;
        return $this->savedPath;
    }
}
