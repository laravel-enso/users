<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use LaravelEnso\Users\Http\Requests\ValidateUser;
use LaravelEnso\Users\Models\User;

class Store extends Controller
{
    use AuthorizesRequests;

    public function __invoke(ValidateUser $request, User $user)
    {
        $user->fill($request->validated());

        $this->authorize('handle', $user);

        tap($user)->save()
            ->sendResetPasswordEmail();

        return [
            'message'  => __('The user was successfully created'),
            'redirect' => 'administration.users.edit',
            'param'    => ['user' => $user->id],
        ];
    }
}
