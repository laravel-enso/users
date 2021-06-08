<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\Users\Http\Controllers\Session\Destroy;
use LaravelEnso\Users\Http\Controllers\Session\Index;

Route::prefix('session')
    ->as('sessions.')
    ->group(function () {
        Route::get('{user}/index', Index::class)->name('index');
        Route::delete('{user}', Destroy::class)->name('destroy');
    });
