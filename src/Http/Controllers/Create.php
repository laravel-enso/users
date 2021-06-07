<?php

namespace LaravelEnso\Users\Http\Controllers;

use Illuminate\Routing\Controller;
use LaravelEnso\People\Models\Person;
use LaravelEnso\Users\Forms\Builders\UserForm;

class Create extends Controller
{
    public function __invoke(Person $person, UserForm $form)
    {
        return ['form' => $form->create($person)];
    }
}
