<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth', 'core'])
    ->prefix('api/administration/users')
    ->as('administration.users.')
    ->group(fn () => require 'app/users.php');
