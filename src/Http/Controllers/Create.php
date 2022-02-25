<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\People\Models\Person;
use LaravelEnso\Users\Forms\Builders\User;

class Create extends Controller
{
    public function __invoke(Person $person, User $form)
    {
        return ['form' => $form->create($person)];
    }
}
