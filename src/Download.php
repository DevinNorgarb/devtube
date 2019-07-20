<?php

namespace DevsWebDev\DevTube;

use DevsWebDev\DevTube\Downloader;
use DevsWebDev\DevTube\MediaDownload;

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
    public function __construct($url, $format = "mp3", $path = null)
    {
        $this->url =  $url;
        $this->path =  $path ?: storage_path(config('devtube.download_path'));
        $this->format = $format ?: config('devtube.default_download');
    }



    /**
     * Runs the download process
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

        return $arr;
    }

    /**
     * [calls the save method]
     * @param  [type] $filePath [description]
     * @return [type]           [description]
     */
    public function save($filePath)
    {
        $this->savedPath = $this->path . "/" . $filePath;
        return $this->savedPath;
    }
}
