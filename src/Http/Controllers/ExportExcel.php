<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Tables\Traits\Excel;
use LaravelEnso\Users\Tables\Builders\User;

class ExportExcel extends Controller
{
    use Excel;

    protected $tableClass = User::class;
}
