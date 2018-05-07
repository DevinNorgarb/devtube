## Install DevTube Video Downloader

Install via composer

```bash
composer require devswebdev/devtube
```

When you install it as a dependency, there need to be a `cache` directory beside `vendor`
and it should be writable (e.g. `chmod 777`).


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
        $dl = new Download($r->url);
        $dl->download();
    }
}
```

<!-- Or if you want to Download and return the download to the view:

```php
return response()->download(storage_path(session($_SERVER['REMOTE_ADDR'])));
``` -->
