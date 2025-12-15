<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'short_code',
        'original_name',
        'disk',
        'storage_path',
        'hls_path',
        'size',
        'mime_type',
        'status',
        'views',
    ];

    protected static function booted(): void
    {
        static::creating(function (Video $video) {
            if (empty($video->short_code)) {
                $video->short_code = Str::random(8);
            }
        });
    }

    public function playbackUrl(): ?string
    {
        if ($this->hls_path) {
            return Storage::disk($this->disk)->url($this->hls_path);
        }

        return Storage::disk($this->disk)->url($this->storage_path);
    }

    public function shareUrl(): string
    {
        return route('videos.show', $this->short_code);
    }
}
