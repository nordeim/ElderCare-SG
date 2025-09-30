<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);
Route::post('/newsletter', NewsletterController::class)->name('newsletter.subscribe');
Route::get('/healthz', function () {
    return response()->json(['status' => 'ok']);
});
