<?php

namespace LaPress\Support;

use Illuminate\Support\Facades\Blade;
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
            'theme',
            ThemeBladeDirectory::get()
        );

        Blade::include('theme::components.image', 'image');
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
