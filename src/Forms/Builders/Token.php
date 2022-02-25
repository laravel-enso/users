<?php

namespace LaravelEnso\Users\Forms\Builders;

use LaravelEnso\Forms\Services\Form;
use LaravelEnso\Users\Models\User;

class Token
{
    private const TemplatePath = __DIR__.'/../Templates/token.json';

    protected Form $form;

    public function __construct()
    {
        $this->form = new Form($this->templatePath());
    }

    public function create(User $user)
    {
        return $this->form
            ->routeParams(['user' => $user->id])
            ->create();
    }

    protected function templatePath(): string
    {
        return self::TemplatePath;
    }
}
