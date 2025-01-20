<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ThemeController;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexController::class)->name('home');

Route::get('/teams', TeamController::class)->name('teams');

Route::post('/set-theme', ThemeController::class)->name('set-theme');

Route::prefix('blog')->name('blog.')->group(static function () {
    Route::get('/', [BlogController::class, 'index'])->name('index');
    Route::get('/{post:slug}', [BlogController::class, 'show'])->name('show');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['role:mod|admin|super-admin'])->prefix('backend')->name('backend.')->group(static function () {
    require_once __DIR__.'/backend.php';
});
