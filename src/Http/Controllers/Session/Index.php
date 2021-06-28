<?php

namespace LaravelEnso\Users\Http\Controllers\Session;

use Illuminate\Routing\Controller;
use LaravelEnso\Users\Http\Resources\Session as Resource;
use LaravelEnso\Users\Models\Session;
use LaravelEnso\Users\Models\User;

class Index extends Controller
{
    public function __invoke(User $user)
    {
        return Resource::collection(Session::for($user)->get());
    }
}
