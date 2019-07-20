## Install DevTube Video Downloader

![alt text](https://devtube.devswebdev.com/img/Screenshot%20from%202018-05-26%2013-54-19.png "devbeats banner")


Install FFMPEG only if you want to convert videos to mp3 etc

Ubuntu:

```bash
sudo apt install ffmpeg
```


Install via composer

```bash
composer require devswebdev/devtube
```

When you install it as a dependency, there need to be a `cache` directory beside `vendor`
and it should be writable (e.g. `chmod 775`).


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
    public function download(Request $r)
    {
        $dl = new Download($r->url, $format);

        //Saves the file to specified directory
        $dl->download();

        // Return as a download
        return response()->download($dl->savedPath);

    }
}
```
