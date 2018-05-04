<?php

namespace DevsWebDev\DevTube\Http;

use App\Http\Controllers\Controller;

class DownloadController extends Controller
{
    public function download()
    {
        $yt = new YouTubeDownloader();
        $links = $yt->getDownloadLinks("https://www.youtube.com/watch?v=QxsmWxxouIM");
        dd($links);
        return "This is my todo list from the controller";
    }
}
