<?php

namespace LaravelEnso\Users;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use LaravelEnso\Users\Models\User;
use LaravelEnso\Users\Policies\User as Policy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        User::class => Policy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
