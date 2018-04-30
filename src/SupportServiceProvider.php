<?php

namespace LaPress\Support;

use Illuminate\Support\ServiceProvider;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        dump('jest');die();
        $this->app['view']->addNamespace('theme', ThemeBladeDirectory::get());
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        dump('jest');die();
    }
}
