<?php

namespace LaravelEnso\Users;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->load();
        ->publish()
        ->mapMorphs();
    }

    private function load()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../config/users.php', 'users');

        return $this;
    }

    private function publish()
    {
        $this->publishes([
            __DIR__ . '/../config' => config_path('laravel-enso'),
        ], 'users-config');

        $this->publishes([
            __DIR__.'/../client/src/js' => base_path('client/src/js'),
        ], 'users-assets');

        return $this;
    }

    private function mapMorphs()
    {
        User::morphMap();

        return $this;
    }

    public function register()
    {
        //
    }
}
