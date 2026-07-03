# DevTube

[![Tests](https://github.com/devswebdev/devtube/actions/workflows/tests.yml/badge.svg)](https://github.com/devswebdev/devtube/actions/workflows/tests.yml)
[![License: MIT](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

A Laravel package for downloading videos (and extracting audio) from the web by passing a URL. DevTube is a thin, modern wrapper around the [`yt-dlp`](https://github.com/yt-dlp/yt-dlp) binary via [`norkunas/youtube-dl-php`](https://github.com/norkunas/youtube-dl-php).

## Requirements

- PHP **8.3+**
- Laravel **12** or **13**
- The [`yt-dlp`](https://github.com/yt-dlp/yt-dlp) binary available on your server
- [`ffmpeg`](https://ffmpeg.org/) (only required for audio extraction, e.g. converting to `mp3`)

### Installing the binaries

```bash
# yt-dlp (recommended install method: pip)
python3 -m pip install -U yt-dlp

# ffmpeg (Ubuntu/Debian) — only needed for mp3/audio extraction
sudo apt install ffmpeg
```

Confirm the binary location so it matches your config:

```bash
which yt-dlp
```

## Installation

```bash
composer require devswebdev/devtube
```

The package registers itself automatically (service provider + `DevTube` facade alias) via Laravel package discovery.

Publish the config file:

```bash
php artisan vendor:publish --provider="DevsWebDev\DevTube\DevTubeServiceProvider" --tag=config
```

This publishes `config/devtube.php`.

## Configuration

```php
return [
    // Path to the yt-dlp binary. Use an absolute path if it is not on the PATH.
    'bin_path' => env('DEVTUBE_YT_DLP_PATH', 'yt-dlp'),

    // Directory (relative to storage_path()) where downloads are saved by default.
    'download_path' => env('DEVTUBE_DOWNLOAD_PATH', 'app/devtube'),

    // Format key (see 'formats') used when none is supplied.
    'default_format' => 'mp4',

    // yt-dlp output filename template.
    'output_template' => '%(title)s.%(ext)s',

    // Per-format yt-dlp options.
    'formats' => [
        'mp4' => [
            'format' => 'mp4',
        ],
        'mp3' => [
            'extract_audio' => true,
            'audio_format' => 'mp3',
            'audio_quality' => '0',
        ],
    ],
];
```

Set the binary path via `.env` if needed:

```dotenv
DEVTUBE_YT_DLP_PATH=/usr/local/bin/yt-dlp
DEVTUBE_DOWNLOAD_PATH=app/devtube
```

## Usage

`download()` returns an `Illuminate\Support\Collection` of `DevsWebDev\DevTube\MediaFile` objects. Each `MediaFile` exposes:

| Member | Type | Description |
| --- | --- | --- |
| `$media->title` | `?string` | The resolved title (null on failure) |
| `$media->file` | `?SplFileInfo` | The downloaded file (null on failure) |
| `$media->error` | `?string` | Error message for this item, or null |
| `$media->path()` | `?string` | Absolute path to the file, or null |
| `$media->wasSuccessful()` | `bool` | Whether this item downloaded successfully |

### Using the facade

```php
use DevsWebDev\DevTube\Facades\DevTube;

$results = DevTube::download('https://www.youtube.com/watch?v=ye5BuYf8q4o', 'mp4');

$media = $results->first();

if ($media->wasSuccessful()) {
    return response()->download($media->path());
}

report($media->error);
```

### Using the `Download` class

```php
use DevsWebDev\DevTube\Download;

$results = (new Download(
    url: 'https://www.youtube.com/watch?v=ye5BuYf8q4o',
    format: 'mp3',
))->download();

$media = $results->first();

return response()->download($media->path());
```

The third argument is an absolute download directory override. When omitted, files are saved under `storage_path(config('devtube.download_path'))`, creating the directory if needed:

```php
DevTube::download($url, 'mp4', '/var/www/storage/app/my-downloads');
```

### Using the Artisan command

```bash
php artisan devtube:download "https://www.youtube.com/watch?v=ye5BuYf8q4o" --format=mp3
php artisan devtube:download "https://www.youtube.com/watch?v=ye5BuYf8q4o" --format=mp4 --path=/absolute/download/dir
```

The command prints a table of results and exits non-zero if any item failed.

## Error handling

- **Per-video problems** (e.g. an unavailable video in a playlist) surface as a `MediaFile` with a non-null `error` and `wasSuccessful() === false`. Always check `wasSuccessful()` before using `path()`.
- **Hard failures** (missing binary, unwritable directory, or a failed `yt-dlp` process) throw `DevsWebDev\DevTube\Exceptions\DownloadException`.

## Upgrading from 2.x to 3.0

3.0 is a breaking release that replaces the old, unmaintained download engines with a single `yt-dlp`-based engine.

**Requirements**
- PHP 8.3+ and Laravel 12/13 are now required.
- Install the `yt-dlp` binary (replacing `youtube-dl`). Point `bin_path` at it. If you still use `youtube-dl`, set `bin_path` to that binary — but `yt-dlp` is recommended.

**Removed**
- The `masih/youtubedownloader` and `athlon1600/youtube-downloader` dependencies, the custom cURL scraper, and the internal classes `MediaDownload`, `DownloadConfig`, and `HelperTrait`.

**Results are objects, not arrays**
- `download()` now returns a `Collection` of `MediaFile` objects. Replace array access with object access:

```php
// 2.x
$media_info = $dl->download()->first();
return response()->download($media_info['file']->getPathname());

// 3.0
$media = $dl->download()->first();
return response()->download($media->path());
```

**Config file changed**
- `config/devtube.php` was rewritten with new keys (`bin_path`, `download_path`, `default_format`, `output_template`, `formats`). Old keys were removed. Re-publish the config and re-apply your settings:

```bash
php artisan vendor:publish --provider="DevsWebDev\DevTube\DevTubeServiceProvider" --tag=config --force
```

**New entry points**
- A `DevTube` facade and a `devtube:download` Artisan command were added. The `Download` class and its `->download()` method are still available.

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see the [License File](LICENSE) for more information.
