<?php

use Illuminate\Support\Facades\Route;
use MichaelBecker\SimpleFile\Http\Controllers\FileController;

Route::prefix('file')
    ->name('file.')
    ->controller(FileController::class)
    ->group(function () {
        Route::get('/file/{disk}/{folder}/{name}', 'show')->name('show');
        Route::delete('/{file}', 'destroy')->name('destroy');
    });
