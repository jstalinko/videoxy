<?php

namespace App\Jobs;

use App\Models\Video;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class ConvertVideoToHls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Video $video) {}

    public function handle(): void
    {
        $inputPath = Storage::disk($this->video->disk)->path($this->video->storage_path);
        $outputDir = 'videos/hls/'.$this->video->short_code;
        $outputPath = Storage::disk($this->video->disk)->path($outputDir);

        if (! is_dir($outputPath)) {
            mkdir($outputPath, 0755, true);
        }

        $outputFile = $outputPath.'/index.m3u8';
        $playlistPath = $outputDir.'/index.m3u8';

        $process = new Process([
            'ffmpeg',
            '-i',
            $inputPath,
            '-vf',
            'scale=1280:-2',
            '-c:v',
            'h264',
            '-c:a',
            'aac',
            '-hls_time',
            '10',
            '-hls_playlist_type',
            'vod',
            '-f',
            'hls',
            $outputFile,
        ]);

        $process->setTimeout(600);
        $process->run();

        if ($process->isSuccessful()) {
            $this->video->update([
                'hls_path' => $playlistPath,
                'status' => 'ready',
            ]);
        } else {
            Log::error('HLS conversion failed', [
                'video_id' => $this->video->id,
                'error' => $process->getErrorOutput(),
            ]);

            $this->video->update([
                'status' => 'failed',
            ]);
        }
    }
}
