
<?php
return [
    "download_path" => env('DOWNLOAD_PATH', 'app/music/'),
    "default_download" => "video",
    "default_videoquality" => 1,
    "default_audioquality" => 320,
    "default_audioformat" => "mp3",
    "download_thumbnail" => true,
    "default_thumbsize" => 'l',
    "ffmpeg_logsActive" => false,
    "ffmpeg_logsDir" => 'logs/',

    "bin_path" => "/usr/bin/youtube-dl",


    "mp3" => [
        'output' => '%(title)s.%(ext)s',
        'metadata-from-title' => '\"%(artist)s - %(title)s\"',
        'add-metadata' => true,
        "ignore-errors" => true,
        "abort-on-error" => false,
        "extract-audio" => true,
    ],
    "default" => [
        'output' => '%(title)s.%(ext)s',
        'metadata-from-title' => '\"%(artist)s - %(title)s\"',
        'add-metadata' => true,
        "ignore-errors" => true,
        "abort-on-error" => false,
    ],
    "video" => [
        'metadata-from-title' => '\"%(artist)s - %(title)s\"',
        'add-metadata' => true,
        'format' => 'mp4',
        "ignore-errors" => true,
        "abort-on-error" => false,
    ]

];
?>
