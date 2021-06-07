<?php

namespace LaravelEnso\Users\Http\Controllers\Token;

use Illuminate\Routing\Controller;
use LaravelEnso\Users\Forms\Builders\TokenForm;
use LaravelEnso\Users\Models\User;

class Create extends Controller
{
    public function __invoke(TokenForm $form, User $user)
    {
        return ['form' => $form->create($user)];
    }
}
