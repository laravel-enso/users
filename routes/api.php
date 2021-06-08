<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/administration')
    ->as('administration.')
    ->group(fn () => require 'app/users.php');
