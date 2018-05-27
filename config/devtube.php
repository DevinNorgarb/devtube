
<?php
    return [
         "download_path" => env('DOWNLOAD_PATH', storage_path('music/')),
         "default_download" => "video",
         "default_videoquality" => 1,
         "default_audioquality" => 320,
         "default_audioformat" => "mp3",
         "download_thumbnail" => true,
         "default_thumbsize" => 'l',
         "ffmpeg_logsActive" => false,
         "ffmpeg_logsDir" => 'logs/',
      ];
  ?>
