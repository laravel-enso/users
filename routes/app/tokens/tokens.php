<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\Users\Http\Controllers\Administration\User\Token\Create;
use LaravelEnso\Users\Http\Controllers\Administration\User\Token\Destroy;
use LaravelEnso\Users\Http\Controllers\Administration\User\Token\Index;
use LaravelEnso\Users\Http\Controllers\Administration\User\Token\Store;

Route::prefix('token')
    ->as('tokens.')
    ->group(function () {
        Route::get('{user}', Create::class)->name('create');
        Route::post('{user}', Store::class)->name('store');
        Route::get('{user}/index', Index::class)->name('index');
        Route::delete('{user}', Destroy::class)->name('destroy');
    });
