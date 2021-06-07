<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Users\Forms\Builders\UserForm;
use LaravelEnso\Users\Models\User;

class Edit extends Controller
{
    public function __invoke(User $user, UserForm $form)
    {
        return ['form' => $form->edit($user)];
    }
}
