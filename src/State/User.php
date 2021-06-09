<?php

namespace LaravelEnso\Users\State;

use Illuminate\Support\Facades\Auth;
use LaravelEnso\Core\Contracts\ProvidesState;
use LaravelEnso\Users\Http\Resources\User as Resource;

class User implements ProvidesState
{
    public function mutation(): string
    {
        return 'setUser';
    }

    public function state(): mixed
    {
        Auth::user()->load(['person', 'avatar', 'role', 'group']);

        return new Resource(Auth::user());
    }
}
