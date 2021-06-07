<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Tables\Traits\Data;
use LaravelEnso\Users\Tables\Builders\UserTable;

class TableData extends Controller
{
    use Data;

    protected $tableClass = UserTable::class;
}
