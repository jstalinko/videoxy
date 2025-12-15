<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVideoRequest;
use App\Http\Resources\VideoResource;
use App\Jobs\ConvertVideoToHls;
use App\Models\Video;
use Inertia\Inertia;
use Inertia\Response;

class VideoController extends Controller
{
    public function store(StoreVideoRequest $request): VideoResource
    {
        $file = $request->file('file');
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

        return VideoResource::make($video);
    }

    public function metadata(Video $video): VideoResource
    {
        return VideoResource::make($video);
    }

    public function show(Video $video): Response
    {
        $video->increment('views');

        return Inertia::render('VideoPlayer', [
            'video' => VideoResource::make($video),
        ]);
    }
}
