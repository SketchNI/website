<?php

use App\Http\Controllers\Backend;
use Illuminate\Support\Facades\Route;

Route::get('/', Backend\IndexController::class)->name('index');
Route::get('/teams', Backend\IndexController::class)->name('teams');

Route::prefix('blog')->name('blog.')->group(function () {
    Route::get('/', [Backend\BlogController::class, 'index'])->name('index');
    Route::get('/create', [Backend\BlogController::class, 'create'])->name('create');
    Route::post('/', [Backend\BlogController::class, 'store'])->name('store');
    Route::get('/{post}/edit', [Backend\BlogController::class, 'edit'])->name('edit');
    Route::put('/{post}/update', [Backend\BlogController::class, 'update'])->name('update');
    Route::delete('/{post}/delete', [Backend\BlogController::class, 'destroy'])->name('destroy');
    Route::get('/{post}/restore', [Backend\BlogController::class, 'restore'])->name('restore');
});

Route::resource('category', Backend\CategoryController::class)->only(['store', 'destroy']);
Route::resource('teams', Backend\TeamController::class)->except('show');
Route::resource('users', Backend\UserController::class);

Route::prefix('pages')->name('pages.')->group(static function () {
    Route::get('/', Backend\IndexController::class)->name('index');
    Route::post('/', Backend\IndexController::class);
    Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    Route::put('/show/{id}', Backend\IndexController::class);
    Route::delete('/show/{id}', Backend\IndexController::class);
});

Route::prefix('support')->name('support.')->group(static function () {
    Route::get('/', [Backend\Support\TicketController::class, 'index'])->name('index');
    Route::get('/show/{ticket}', [Backend\Support\TicketController::class, 'show'])->name('show');
});

Route::resource('images', Backend\ImageController::class)
    ->except(['edit', 'create']);

Route::prefix('finance')->name('finance.')->group(static function () {
    Route::prefix('invoices')->name('invoices.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    });

    Route::prefix('customers')->name('customers.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    });
});

Route::prefix('misc')->name('misc.')->group(static function () {
    Route::get('/', Backend\IndexController::class)->name('index');

    Route::prefix('audit-logs')->name('audit-log.')->group(static function () {
        Route::get('/', Backend\AuditLogController::class)->name('index');
    });

    Route::prefix('statistics')->name('statistics.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    });

    Route::prefix('reports')->name('reports.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    });

    Route::prefix('scheduler')->name('scheduler.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    });
});
