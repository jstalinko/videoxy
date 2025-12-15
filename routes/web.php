<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\VideoController;
use App\Models\Ad;
use App\Models\Video;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('/v/{video:short_code}', [VideoController::class, 'show'])->name('videos.show');

Route::prefix('api')->group(function () {
    Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video:short_code}', [VideoController::class, 'metadata']);
    Route::get('/ads/{placement}', [AdController::class, 'placement']);
});

Route::get('/ads/click/{ad:slug}', [AdController::class, 'redirect'])
    ->name('ads.redirect')
    ->middleware('signed');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/videos', function () {
        return Inertia::render('admin/ManageVideos', [
            'videos' => Video::latest()->take(50)->get(),
        ]);
    })->name('dashboard.videos');

    Route::get('/dashboard/ads', function () {
        return Inertia::render('admin/ManageAds', [
            'ads' => Ad::latest()->get(),
        ]);
    })->name('dashboard.ads');
});

require __DIR__.'/settings.php';
