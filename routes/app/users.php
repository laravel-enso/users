<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\Users\Http\Controllers\Create;
use LaravelEnso\Users\Http\Controllers\Destroy;
use LaravelEnso\Users\Http\Controllers\Edit;
use LaravelEnso\Users\Http\Controllers\ExportExcel;
use LaravelEnso\Users\Http\Controllers\InitTable;
use LaravelEnso\Users\Http\Controllers\Options;
use LaravelEnso\Users\Http\Controllers\ResetPassword;
use LaravelEnso\Users\Http\Controllers\Show;
use LaravelEnso\Users\Http\Controllers\Store;
use LaravelEnso\Users\Http\Controllers\TableData;
use LaravelEnso\Users\Http\Controllers\Update;

Route::prefix('users')
    ->as('users.')
    ->group(function () {
        Route::get('create/{person}', Create::class)->name('create');
        Route::post('', Store::class)->name('store');
        Route::get('{user}/edit', Edit::class)->name('edit');
        Route::patch('{user}', Update::class)->name('update');
        Route::delete('{user}', Destroy::class)->name('destroy');

        Route::get('initTable', InitTable::class)->name('initTable');
        Route::get('tableData', TableData::class)->name('tableData');
        Route::get('exportExcel', ExportExcel::class)->name('exportExcel');

        Route::get('options', Options::class)->name('options');

        Route::get('{user}', Show::class)->name('show');

        Route::post('{user}/resetPassword', ResetPassword::class)->name('resetPassword');

        require __DIR__.'/token/tokens.php';
        require __DIR__.'/session/sessions.php';
    });
