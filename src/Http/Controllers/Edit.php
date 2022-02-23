<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\Users\Forms\Builders\User as Form;
use LaravelEnso\Users\Models\User;

class Edit extends Controller
{
    public function __invoke(User $user, Form $form)
    {
        return ['form' => $form->edit($user)];
    }
}
