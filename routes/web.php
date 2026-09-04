<?php

use App\Http\Controllers\JourneyVettingController;
use App\Http\Controllers\KnowledgeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/journey', 'journey')->name('journey');
Route::view('/knowledge/children-maintenance/examples', 'knowledge.children-maintenance-examples')
    ->name('knowledge.children-maintenance.examples');
Route::get('/knowledge/{guide}', [KnowledgeController::class, 'show'])
    ->whereIn('guide', ['children-maintenance', 'property', 'alimony', 'joint-petition'])
    ->name('knowledge.show');
Route::get('/vetting-dashboard', [JourneyVettingController::class, 'dashboard'])->name('journey.dashboard');

Route::prefix('journey')->name('journey.')->group(function () {
    Route::post('/tac/send', [JourneyVettingController::class, 'sendTac'])
        ->middleware('throttle:5,10')
        ->name('tac.send');
    Route::post('/tac/verify', [JourneyVettingController::class, 'verifyTac'])
        ->middleware('throttle:10,10')
        ->name('tac.verify');
    Route::post('/submit', [JourneyVettingController::class, 'store'])
        ->middleware('throttle:5,60')
        ->name('submit');
});
