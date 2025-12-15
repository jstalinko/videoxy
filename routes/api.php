<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;

Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');
Route::get('/videos/{video:short_code}', [VideoController::class, 'metadata']);
Route::get('/ads/{placement}', [AdController::class, 'placement']);
