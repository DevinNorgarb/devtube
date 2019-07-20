## Install DevTube Video Downloader

![alt text](https://devtube.devswebdev.com/img/Screenshot%20from%202018-05-26%2013-54-19.png "devbeats banner")

Install FFMPEG only if you want to convert videos to mp3 etc

Ubuntu:

```bash
sudo apt install ffmpeg
```

Install `youtube-dl`

```bash
sudo apt install youtube-dl
```

Install via composer

```bash
composer require devswebdev/devtube
```

<!-- The package will automatically register itself.

```bash
php artisan vendor:publish --provider="DevsWebDev\DevTube\DevTubeServiceProvider" --tag="migrations"
`` -->

Publish vendor assets

```bash
php artisan vendor:publish --provider="DevsWebDev\DevTube\DevTubeServiceProvider"
```

An Example

```php

namespace App\Http\Controllers;
use DevsWebDev\DevTube\Download;

class YoutubeDownloadController extends Controller
{
    public function download()
    {
    $dl = new Download($url = "https://www.youtube.com/watch?v=ye5BuYf8q4o", $format = "mp4", $download_path = "music" );

    //Saves the file to specified directory
    $media_info = $dl->download();
    $media_info = $media_info->first();

    // Return as a download
    return response()->download($media_info['file']->getPathname());

    }
}
```

Or in your web.php routes file

```php
use DevsWebDev\DevTube\Download;

Route::get('/', function () {
    $dl = new Download($url = "https://www.youtube.com/watch?v=ye5BuYf8q4o", $format = "mp3", $download_path = "music" );

    //Saves the file to specified directory
    $media_info = $dl->download();
    $media_info = $media_info->first();

    // Return as a download
    return response()->download($media_info['file']->getPathname());

});

```
