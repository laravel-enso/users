<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Tables\Traits\Init;
use LaravelEnso\Users\Tables\Builders\UserTable;

class InitTable extends Controller
{
    use Init;

    protected $tableClass = UserTable::class;
}
