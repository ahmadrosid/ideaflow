<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OutlineController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('outlines', OutlineController::class);
Route::get('outlines/generate/{id}', [OutlineController::class, 'generate'])->name('outlines.generate');
