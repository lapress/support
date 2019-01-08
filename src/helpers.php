<?php

use LaPress\Models\Post;
use LaPress\Models\Repositories\Repository;

if (!function_exists('wordpress_path')) {
    function wordpress_path($path = null)
    {
        $path = str_replace('%20', ' ', $path);

        return storage_path('framework/wordpress/'.$path);
    }
}

if (!function_exists('wordpress')) {
    function wordpress($global_variable)
    {
        if (!array_key_exists($global_variable, app('wordpress.globals'))) {
            return;
        }

        return $GLOBALS[$global_variable];
    }
}

if (!function_exists('wordpress_table')) {
    function wordpress_table($table)
    {
        return env('WP_TABLE_PREFIX', 'wp_').$table;
    }
}

if (!function_exists('wordpress_installed')) {
    function wordpress_installed()
    {
        try {
            return app('db')->table(wordpress_table('options'))->exists();
        } catch (Exception $ex) {
            return false;
        }
    }
}

if (!function_exists('wordpress_multisite_installed')) {
    function wordpress_multisite_installed()
    {
        try {
            return app('db')->table(wordpress_table('site'))->exists();
        } catch (Exception $ex) {
            return false;
        }
    }
}

if (!function_exists('logger')) {
    function logger()
    {
        return app(\Psr\Log\LoggerInterface::class);
    }
}

if (!function_exists('debug_log')) {
    /**
     * @signature debug_log(mixed $var)
     * @signature debug_log(string label, mixed $var, array $context = [])
     */
    function debug_log()
    {
        if (!env('APP_DEBUG', false)) {
            return;
        }
        $label = null;

        if (func_num_args() < 1) {
            throw new InvalidArgumentException('Missing argument');
        } elseif (func_num_args() == 1) {
            $var = func_get_arg(0);
            $context = [];
        } elseif (func_num_args() == 2) {
            $label = func_get_arg(0);
            $var = func_get_arg(1);
            $context = [];
        } elseif (func_num_args() >= 3) {
            $label = func_get_arg(0);
            $var = func_get_arg(1);
            $context = func_get_arg(2);
        }

        logger()->debug(($label ? "$label: " : '').print_r($var, true), $context);
    }
}

if (!function_exists('var_log')) {
    /**
     * @signature var_log(array $vars)
     * @signature var_log(string $name, mixed $var)
     */
    function var_log()
    {
        $vars = [];
        if (func_num_args() < 1) {
            throw new InvalidArgumentException('Missing argument');
        } elseif (func_num_args() == 1) {
            $vars = func_get_arg(0);
        } elseif (func_num_args() == 2) {
            $vars = [func_get_arg(0) => func_get_arg(1)];
        }

        foreach ($vars as $name => $var) {
            logger()->debug("[VAR] $name: ".print_r($var, true), []);
        }
    }
}


if (!function_exists('themes')) {
    function themes($path = '')
    {
        return resource_path('themes/'.$path);
    }
}


if (!function_exists('theme')) {
    function theme_path($path)
    {
        return asset(config('wordpress.theme.active').'/dist/'.$path);
    }
}


if (!function_exists('repository')) {
    function repository($name)
    {
        $key = config('wordpress.posts.map.'.$name);

        if (empty($key)) {
            $key = Post::class;
        }

        if (class_exists($name)) {
            $key = $name;
        }

        return new Repository(app($key));
    }
}

if (!function_exists('menu')) {
    function menu(string $location)
    {
        return repository(LaPress\Models\Menu::class)->location($location)->first();
    }
}

if (!function_exists('theme')) {
    function theme()
    {
        return config('wordpress.theme.active') ?: 'theme';
    }
}

if (!function_exists('theme_view')) {
    function theme_view(string $view)
    {
        return 'theme::'.$view;
    }
}

if (!function_exists('theme_mix')) {
    function theme_mix($path)
    {
        return mix($path, '/'.theme().'/public');
    }
}
