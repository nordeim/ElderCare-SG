<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::post('/locale/{locale}', LocaleController::class)->name('locale.switch');
Route::post('/newsletter', NewsletterController::class)->name('newsletter.subscribe');
Route::post('/assessment-insights', AssessmentController::class)->name('assessment.submit');
Route::get('/api/availability', AvailabilityController::class)
    ->middleware('throttle:availability')
    ->name('availability.index');
Route::get('/healthz', function () {
    return response()->json(['status' => 'ok']);
});
