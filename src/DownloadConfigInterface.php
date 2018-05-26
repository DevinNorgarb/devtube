<?php

namespace DevsWebDev\DevTube;

interface DownloadConfigInterface
{
    // /**
    //  *  Set the directory (relative path to class file)
    //  *  where to save the downloads to.
    //  */
    const Download_Folder = config('devtube.download_path');

    // /**
    //  *  Set the default download type.
    //  *  Choose one of 'video', or 'audio'.
    //  *
    //  *  Defines whether to download the video files, or to extract
    //  *  and convert the soundtrack into an audio file by default.
    //  *
    //  *  NOTE: To extract the soundtrack from the video file and convert
    //  *  it into an audio file, you must have Ffmpeg and, depending on
    //  *  the video filetype, additional media libraries installed on the
    //  *  server that hosts the scripts!
    //  */
    const Default_Download = config('devtube.default_download');

    // /**
    //  *  Set the default video output quality.
    //  *  Choose integer '1' (One) to download videos in the best quality available,
    //  *  or integer '0' (Zero) for the lowest quality (,thus smallest file size).
    //  */
    const Default_Videoquality = config('devtube.default_videoquality');

    // /**
    //  *  Set the default audio quality (sample rate in kbits).
    //  *  Choose any integer value between 128 (low quality) and 320 (CD quality).
    //  *
    //  *  Note: Max. output quality depends on the video input file. Thus, the
    //  *  converted mp3 output file    may be worse, than expected from the value set.
    //  */
    const Default_Audioquality = config('devtube.default_audioquality');

    // /**
    //  *  Set the default audio output filetype.
    //  *  Choose one of "mp3", "wav", "ogg", or "mp4".
    //  */
    const Default_Audioformat = config('devtube.default_audioformat');

    // /**
    //  *  Set the video preview image preference.
    //  *  Choose '1' (nummeric One) to download a preview image for the video,
    //  *  or '0' (nummeric Zero) to download only the video itself.
    //  */
    const Download_Thumbnail = config('devtube.download_thumbnail');

    // /**
    //  *  Set the video preview image size.
    //  *  Choose 'l' (small letter "L") for a size of 480*360px,
    //  *  or 's' for a size of 120*90px.
    //  */
    const Default_Thumbsize = config('devtube.default_thumbsize');

    // /**
    //  *  Set the directory (absolute path, trailing slash!)
    //  *  where to save Ffmpeg log files to.
    //  */
    const Ffmpeg_LogsActive = config('devtube.ffmpeg_logsActive');

    // /**
    //  *  Set the directory (absolute path, trailing slash!)
    //  *  where to save Ffmpeg log files to.
    //  */
    const Ffmpeg_LogsDir = config('devtube.ffmpeg_logsDir');
}
