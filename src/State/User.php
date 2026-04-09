<?php

namespace LaravelEnso\Users\State;

use Illuminate\Support\Facades\Auth;
use LaravelEnso\Core\Contracts\ProvidesState;
use LaravelEnso\Users\Http\Resources\User as Resource;

class User implements ProvidesState
{
    public function store(): string
    {
        return 'app';
    }

    public function state(): array
    {
        Auth::user()->load(['person', 'avatar', 'role', 'group']);

        return ['user' => (new Resource(Auth::user()))->resolve()];
    }
}
