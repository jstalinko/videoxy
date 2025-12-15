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
use Throwable;

class ConvertVideoToHls implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Video $video) {}

    public function handle(): void
    {
        $disk = Storage::disk($this->video->disk);

        if (! $disk->exists($this->video->storage_path)) {
            Log::warning('Original video missing for conversion', [
                'video_id' => $this->video->id,
                'storage_path' => $this->video->storage_path,
            ]);
            $this->video->update(['status' => 'failed']);

            return;
        }

        $inputPath = $disk->path($this->video->storage_path);
        $outputDir = 'videos/hls/'.$this->video->short_code;
        $disk->makeDirectory($outputDir);

        $outputFile = $disk->path($outputDir.'/index.m3u8');
        $playlistPath = $outputDir.'/index.m3u8';
        $segmentsPattern = $disk->path($outputDir.'/%03d.ts');

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
            '6',
            '-hls_playlist_type',
            'vod',
            '-hls_segment_filename',
            $segmentsPattern,
            '-f',
            'hls',
            $outputFile,
        ]);

        try {
            $process->setTimeout(600);
            $process->mustRun();

            $this->video->update([
                'hls_path' => $playlistPath,
                'status' => 'ready',
            ]);
        } catch (Throwable $exception) {
            Log::error('HLS conversion failed', [
                'video_id' => $this->video->id,
                'error' => $exception->getMessage(),
                'stderr' => method_exists($exception, 'getProcess') ? $exception->getProcess()->getErrorOutput() : null,
            ]);

            $this->video->update([
                'status' => 'failed',
            ]);
        }
    }
}
