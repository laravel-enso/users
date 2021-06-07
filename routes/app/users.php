<?php

use Illuminate\Support\Facades\Route;
use LaravelEnso\Users\Http\Controllers\User\Create;
use LaravelEnso\Users\Http\Controllers\User\Destroy;
use LaravelEnso\Users\Http\Controllers\User\Edit;
use LaravelEnso\Users\Http\Controllers\User\ExportExcel;
use LaravelEnso\Users\Http\Controllers\User\InitTable;
use LaravelEnso\Users\Http\Controllers\User\Options;
use LaravelEnso\Users\Http\Controllers\User\ResetPassword;
use LaravelEnso\Users\Http\Controllers\User\Show;
use LaravelEnso\Users\Http\Controllers\User\Store;
use LaravelEnso\Users\Http\Controllers\User\TableData;
use LaravelEnso\Users\Http\Controllers\User\Update;

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

        require 'token/tokens.php';
        require 'session/sessions.php';
    });
