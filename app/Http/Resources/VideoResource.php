<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->original_name,
            'short_code' => $this->short_code,
            'status' => $this->status,
            'size_mb' => round($this->size / 1024 / 1024, 2),
            'views' => $this->views,
            'share_url' => $this->shareUrl(),
            'playback_url' => $this->playbackUrl(),
        ];
    }
}
