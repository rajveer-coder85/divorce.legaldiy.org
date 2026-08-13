<?php

use App\Http\Controllers\JointPetitionController;
use App\Http\Controllers\KnowledgeController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/', '/joint-divorce')->name('home');
Route::get('/joint-divorce', fn (JointPetitionController $controller) => $controller->show())->name('joint-divorce');
Route::get('/joint-divorce/{slug}', fn (JointPetitionController $controller, string $slug) => $controller->show($slug))->name('joint-divorce-guide');

// Redirect the temporary public structure to the master Joint Divorce journey.
Route::permanentRedirect('/joint-petition', '/joint-divorce');
Route::permanentRedirect('/joint-petition/readiness', '/joint-divorce/readiness');
Route::permanentRedirect('/joint-petition/what-we-need-to-agree', '/joint-divorce/decisions');
Route::permanentRedirect('/joint-petition/not-suitable', '/joint-divorce/not-suitable');
Route::permanentRedirect('/joint-petition/why-agreement-matters', '/joint-divorce/agreement');

// Existing assessment functionality is intentionally retained.
Route::get('/assessment', fn (KnowledgeController $controller) => $controller->page('assessment'))->name('assessment');
