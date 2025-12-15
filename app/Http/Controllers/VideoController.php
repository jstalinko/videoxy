<?php

namespace App\Http\Controllers;

use App\Jobs\ConvertVideoToHls;
use App\Models\Video;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class VideoController extends Controller
{
    public function store(Request $request): JsonResource
    {
        $validated = $request->validate([
            'file' => 'required|file|max:2048|mimetypes:video/*',
        ]);

        $file = $validated['file'];
        $path = $file->store('videos/uploads', 'public');

        $video = Video::create([
            'original_name' => $file->getClientOriginalName(),
            'disk' => 'public',
            'storage_path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'status' => 'processing',
        ]);

        ConvertVideoToHls::dispatch($video);

        return JsonResource::make([
            'short_code' => $video->short_code,
            'share_url' => $video->shareUrl(),
            'playback_url' => $video->playbackUrl(),
            'status' => $video->status,
            'size_mb' => round($video->size / 1024 / 1024, 2),
            'name' => $video->original_name,
        ]);
    }

    public function show(Video $video): Response
    {
        $video->increment('views');

        return Inertia::render('VideoPlayer', [
            'video' => [
                'name' => $video->original_name,
                'short_code' => $video->short_code,
                'views' => $video->views,
                'status' => $video->status,
                'playback_url' => $video->playbackUrl(),
                'share_url' => $video->shareUrl(),
            ],
        ]);
    }

    public function metadata(Video $video): JsonResource
    {
        return JsonResource::make([
            'name' => $video->original_name,
            'short_code' => $video->short_code,
            'views' => $video->views,
            'status' => $video->status,
            'playback_url' => $video->playbackUrl(),
            'share_url' => $video->shareUrl(),
        ]);
    }
}
