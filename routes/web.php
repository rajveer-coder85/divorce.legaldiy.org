<?php

use App\Http\Controllers\JointPetitionController;
use App\Http\Controllers\KnowledgeController;
use Illuminate\Support\Facades\Route;

Route::permanentRedirect('/', '/joint-petition')->name('home');
Route::get('/joint-petition', fn (JointPetitionController $controller) => $controller->show())->name('joint-petition');
Route::get('/joint-petition/{slug}', fn (JointPetitionController $controller, string $slug) => $controller->show($slug))->name('joint-petition-guide');

// Existing assessment functionality is intentionally retained.
Route::get('/assessment', fn (KnowledgeController $controller) => $controller->page('assessment'))->name('assessment');
