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
    public function __construct($url = "https://www.youtube.com/watch?v=QxsmWxxouIM", $format = "mp3")
    {

        // $url = "https://www.youtube.com/playlist?list=RDCNUTlKqSO-I";
        // $url = "https://www.youtube.com/watch?v=oG6YKKWY0hA";

        // $media_info = MediaDownload::getPlaylistIds($url, $format);
        // $arr = [];

        // foreach ($media_info as $key => $info) {
        //     $res = MediaDownload::download($info, $format);

        //     if (!is_array($res)) {
        //         continue;
        //     }
        //     dump($res);
        //     $info = json_decode($info, true);
        //     $arr[] = $info;
        //     // $info['webpage_url'] = $info['id'];
        // }

        // dump("arr", $arr);

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


        $media_info = (new MediaDownload(
            $this->url,
            $this->format
        ))->getPlaylistIds();


        $arr = [];
        foreach ($media_info as $key => $info) {
            $res = MediaDownload::download($info, $this->format);
            dd($res);
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
