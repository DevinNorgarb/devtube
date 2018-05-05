<?php

namespace DevsWebDev\DevTube;

use Illuminate\Support\Facades\Storage;
use Masih\YoutubeDownloader\YoutubeDownloader;

class Download
{
    public $youtube;

    public $path;

    public $savedPath;


    public function __construct($url, $path)
    {
        $this->url =  $url;
        $this->path =  storage_path("app/public/music");
    }

    public function download()
    {
        $youtube = new YoutubeDownloader($this->url);
        $youtube->setPath($this->path);
        $this->youtube = $youtube;

        $youtube->onProgress = function ($downloadedBytes, $fileSize, $index, $count) {
            if ($count > 1) {
                // echo '[' . $index . ' of ' . $count . ' videos] ';
            }
            if ($fileSize > 0) {
                // echo "\r" . 'Downloaded ' . $downloadedBytes . ' of ' . $fileSize . ' bytes [%' . number_format($downloadedBytes * 100 / $fileSize, 2) . '].';
            } else {
                // echo "\r" . 'Downloading...';
            }
        };

        $youtube->onComplete = function ($filePath, $fileSize, $index, $count) {
            return  $this->redirect($filePath);
        };

        $youtube->download();
    }

    public function redirect($filePath)
    {
        $youtube = $this->youtube;
        $file = basename($filePath);


        // $files = \Storage::files('public/music');
        // dump($files);
        session([$_SERVER['REMOTE_ADDR'] => 'app/public/music/'.$file]);

        // \Session::put('public/music/'.$file:'public/music/'.$file)
        // dump('public/music/'.$file);
        // $dl = Storage::download('public/music/'.$file);
        // // return $dl;
        // dump($dl);
        // $contents = Storage::get('public/music/'.$file);
        // // return json_encode($contents);
        // // dump($contents);
        // $url = \Storage::url('public/music/'.$file);
        // echo $url;
    }
}
