<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Users\Models\User;
use LaravelEnso\Users\Services\ProfileBuilder;

class Show extends Controller
{
    use AuthorizesRequests;

    public function __invoke(User $user)
    {
        $this->authorize('profile', $user);

        (new ProfileBuilder($user))->set();

        return ['user' => $user];
    }
}
