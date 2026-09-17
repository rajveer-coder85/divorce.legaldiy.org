<?php

use App\Http\Controllers\JourneyVettingController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\DashboardAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('/journey', 'journey')->name('journey');
Route::view('/contested-divorce-support', 'contested-divorce-coming-soon')->name('contested-divorce.support');
Route::view('/knowledge/children-maintenance/examples', 'knowledge.children-maintenance-examples')
    ->name('knowledge.children-maintenance.examples');
Route::get('/knowledge/{guide}/examples', [KnowledgeController::class, 'examples'])
    ->whereIn('guide', ['property', 'alimony', 'joint-petition'])
    ->name('knowledge.examples');
Route::get('/knowledge/{guide}', [KnowledgeController::class, 'show'])
    ->whereIn('guide', ['children-maintenance', 'property', 'alimony', 'joint-petition'])
    ->name('knowledge.show');
Route::middleware('guest')->group(function () {
    Route::get('/dashboard/login', [DashboardAuthController::class, 'create'])->name('dashboard.login');
    Route::post('/dashboard/login', [DashboardAuthController::class, 'store'])->middleware('throttle:5,1')->name('dashboard.login.submit');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [JourneyVettingController::class, 'dashboard'])->name('journey.dashboard');
    Route::post('/dashboard/submissions/{submission}/slug', [JourneyVettingController::class, 'createSlug'])->name('journey.dashboard.slug');
    Route::post('/dashboard/submissions/{submission}/access', [JourneyVettingController::class, 'toggleSlugAccess'])->name('journey.dashboard.access');
    Route::post('/dashboard/submissions/{submission}/slug/email', [JourneyVettingController::class, 'sendSlugEmail'])->middleware('throttle:5,10')->name('journey.dashboard.slug.email');
    Route::get('/vetting-dashboard', fn () => redirect()->route('journey.dashboard'));
    Route::post('/dashboard/logout', [DashboardAuthController::class, 'destroy'])->name('dashboard.logout');
});
Route::get('/case/{slug}', [JourneyVettingController::class, 'accessLink'])->name('journey.access');
Route::post('/case/{slug}/tac', [JourneyVettingController::class, 'sendAccessTac'])->middleware('throttle:3,10')->name('journey.access.tac');
Route::post('/case/{slug}/unlock', [JourneyVettingController::class, 'unlockAccessLink'])->middleware('throttle:5,1')->name('journey.access.unlock');
Route::post('/case/{slug}/login', [JourneyVettingController::class, 'loginAccessLink'])->middleware('throttle:5,1')->name('journey.access.login');
Route::post('/case/{slug}/terminate', [JourneyVettingController::class, 'terminateAccessLink'])->name('journey.access.terminate');

Route::prefix('inquiry')->name('inquiry.')->group(function () {
    Route::post('/tac/send', [InquiryController::class, 'sendTac'])->middleware('throttle:5,10')->name('tac.send');
    Route::post('/tac/verify', [InquiryController::class, 'verifyTac'])->middleware('throttle:10,10')->name('tac.verify');
    Route::post('/submit', [InquiryController::class, 'store'])->middleware('throttle:5,60')->name('submit');
});

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
