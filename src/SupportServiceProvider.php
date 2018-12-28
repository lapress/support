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
        $this->app['view']->addNamespace(
            config('wordpress.theme.active'),
            ThemeBladeDirectory::get()
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}
