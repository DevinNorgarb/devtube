<?php

return [
    // Path to the yt-dlp (or youtube-dl) binary. Use an absolute path if it is
    // not on the server PATH, e.g. '/usr/local/bin/yt-dlp'.
    'bin_path' => env('DEVTUBE_YT_DLP_PATH', 'yt-dlp'),

    // Directory (relative to storage_path()) where downloads are saved.
    'download_path' => env('DEVTUBE_DOWNLOAD_PATH', 'app/devtube'),

    // Format key (see 'formats' below) used when none is supplied.
    'default_format' => 'mp4',

    // yt-dlp output filename template.
    'output_template' => '%(title)s.%(ext)s',

    // Per-format yt-dlp options mapped onto norkunas Options.
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
