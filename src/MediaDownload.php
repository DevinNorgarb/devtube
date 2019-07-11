<?php

namespace DevsWebDev\DevTube;

// dd(__DIR__ . '../vendor/autoload.php');
require __DIR__ . './../vendor/autoload.php';

use DevsWebDev\DevTube\Traits\HelperTrait;
use DevsWebDev\DevTube\DownloadConfig;
use YoutubeDl\YoutubeDl;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;



if (!function_exists('curl_init')) {
    throw new \Exception('Script requires the PHP CURL extension.');
    exit(0);
}
if (!function_exists('json_decode')) {
    throw new \Exception('Script requires the PHP JSON extension.');
    exit(0);
}


class MediaDownload
{
    use HelperTrait;

    public static function getPlaylistIds($url, $format)
    {


        $path = storage_path(config('devtube.download_path'));
        $rand = rand(0, 1000000);

        if ($format == 'mp4') {
            $format = '-f mp4';
        }


        $limit = '';
        if (env('TEST')) {
            $limit = ' --playlist-end 3';
        }

        $command = 'cd ' . $path .
            ' && youtube-dl -j -i  --playlist-start="1" --playlist-end="5"  --skip-download ' . " $limit"  . " $url" . " > $rand.json  ";

        shell_exec($command);
        $fp = fopen($path . "/" . "$rand.json", 'r');
        $filesize = filesize($path . "/" . "$rand.json");
        $array = explode("\n", @fread($fp, $filesize));

        $array = array_filter($array);
        if (empty($array[0])) {
            return [];
        }

        if (in_array("null", array_values($array))) {
            $array = [];
            $array[] = $url;
            return $array;
        }

        $all_videos = [];
        foreach ($array as $key => &$value) {
            $all_videos[$key] = json_decode($value, true);
        }

        return $all_videos;
    }

    public static function download($array, $format)
    {

        $options = config('devtube.default');
        $path = storage_path(config('devtube.download_path'));

        $youtube_dl_bin_path = config('devtube.bin_path');

        if ($format == 'mp4') {
            $options = config('devtube.video');
        } else if ($format == 'mp3') {
            $options = config('devtube.mp3');
            $options['extract-audio'] = true;
            $options['audio-format'] = 'mp3';
            $options['embed-thumbnail'] = true;
        }

        if (empty($array['id'])) {
            return;
        }

        $downloader = new YoutubeDl($options);
        $downloader->setDownloadPath($path);
        $downloader->setBinPath($youtube_dl_bin_path);

        $dl = [];

        try {
            $invalid_url_error = false;
            $dl = $downloader->download($array['webpage_url']);
        } catch (\Exception $e) {
            $invalid_url_error = true;
        }

        if ($invalid_url_error) {
            try {
                $dl = $downloader->download($array['id']);
            } catch (\Exception $e) {
                // throw $e;
            }
        }

        return  $dl;
    }
}
