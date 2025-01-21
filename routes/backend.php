<?php

use App\Http\Controllers\Backend;
use Illuminate\Support\Facades\Route;

Route::get('/', Backend\IndexController::class)->name('index');
Route::get('/teams', Backend\IndexController::class)->name('teams');

Route::resource('blog', Backend\BlogController::class)->withTrashed()->except('show');
Route::resource('category', Backend\CategoryController::class)->only(['store', 'destroy']);
Route::resource('teams', Backend\TeamController::class)->except('show');

Route::prefix('users')->name('users.')->group(static function () {
    Route::get('/', Backend\IndexController::class)->name('index');
    Route::get('/show/{id}', Backend\IndexController::class)->name('show');
});

Route::prefix('pages')->name('pages.')->group(static function () {
    Route::get('/', Backend\IndexController::class)->name('index');
    Route::post('/', Backend\IndexController::class);
    Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    Route::put('/show/{id}', Backend\IndexController::class);
    Route::delete('/show/{id}', Backend\IndexController::class);
});

Route::prefix('support')->name('support.')->group(static function () {
    Route::get('/', Backend\IndexController::class)->name('index');
    Route::get('/show/{id}', Backend\IndexController::class)->name('show');
});

Route::prefix('images')->name('images.')->group(static function () {
    Route::get('/', Backend\IndexController::class)->name('index');
    Route::get('/show/{id}', Backend\IndexController::class)->name('show');
});

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

Route::prefix('infrastructure')->name('infrastructure.')->group(static function () {
    Route::prefix('builds')->name('builds.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    });

    Route::prefix('releases')->name('releases.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    });

    Route::prefix('runners')->name('runners.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    });

    Route::prefix('issues')->name('issues.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
    });
});

Route::prefix('misc')->name('misc.')->group(static function () {
    Route::get('/', Backend\IndexController::class)->name('index');

    Route::prefix('audit-logs')->name('audit-log.')->group(static function () {
        Route::get('/', Backend\IndexController::class)->name('index');
        Route::get('/show/{id}', Backend\IndexController::class)->name('show');
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
