<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class AdController extends Controller
{
    public function placement(string $placement): JsonResource
    {
        $ad = Ad::where('placement', $placement)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (! $ad) {
            return JsonResource::make(null);
        }

        $signedUrl = URL::signedRoute('ads.redirect', ['ad' => $ad->slug]);

        return JsonResource::make([
            'title' => $ad->title,
            'slug' => $ad->slug,
            'placement' => $ad->placement,
            'safe_url' => $signedUrl,
        ]);
    }

    public function redirect(Ad $ad, Request $request): RedirectResponse
    {
        return redirect()->away($ad->target_url);
    }
}
