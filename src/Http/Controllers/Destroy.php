<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelEnso\Users\Models\User;

class Destroy extends Controller
{
    use AuthorizesRequests;

    public function __invoke(Request $request, User $user)
    {
        $this->authorize('handle', $user);

        $user->erase($request->boolean('person'));

        return [
            'message'  => __('The user was successfully deleted'),
            'redirect' => 'administration.users.index',
        ];
    }
}
