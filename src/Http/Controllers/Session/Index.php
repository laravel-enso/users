<?php

namespace LaravelEnso\Users\Http\Controllers\Session;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Users\Http\Resources\Session as Resource;
use LaravelEnso\Users\Models\Session;
use LaravelEnso\Users\Models\User;

class Index extends Controller
{
    use AuthorizesRequests;

    public function __invoke(User $user)
    {
        $this->authorize('sessions', $user);

        return Resource::collection(Session::for($user)->get());
    }
}
