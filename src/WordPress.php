<?php

namespace LaPress\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class WordPress
{
    /** @var array */
    private static $definition;

    /** @var array */
    private static $script_globals;

    /**
     * @return array
     * @throws \Exception
     */
    public static function blogAdminScripts()
    {
        static::setup();

        return array_keys(static::$definition['blog_admin_scripts']);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function siteAdminScripts()
    {
        static::setup();

        return array_keys(static::$definition['site_admin_scripts']);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function scriptGlobals()
    {
        static::setup();

        return static::$script_globals;
    }

    /**
     * @param $script_path
     * @return mixed
     * @throws \Exception
     */
    public static function globals($script_path)
    {
        return Arr::get(static::scriptGlobals(), $script_path);
    }

    /**
     * @return mixed|void
     */
    public static function activePlugins()
    {
        return get_option('active_plugins');
    }

    /**
     * @param string $path
     * @return string
     */
    public static function path(string $path = '')
    {
        $path = str_replace('%20', ' ', $path);
        $path = Str::startsWith($path, '/') ? $path : '/'.$path;
        $basePath = config('wordpress.core') ?: base_path('../wordpress');

        return str_replace('//', '/', $basePath.$path);
    }

    public static function content($path = '')
    {
        return base_path('../content/'.$path);
    }

    /**
     * @param $plugin
     * @return bool|string
     */
    public static function pluginPath($plugin)
    {
        $path = static::path('wp-content/plugins/').$plugin.'/'.$plugin.'.php';

        if (!file_exists($path)) {
            $path = static::path('wp-content/plugins/').$plugin.'.php';

            if (!file_exists($path)) {
                return false;
            }
        }

        return $path;
    }

    /**
     * @return string
     */
    public static function activetheme()
    {
        return get_template();
    }

    /**
     * @param $theme
     * @return bool|string
     */
    public static function themePath($theme)
    {
        $path = static::path('wp-content/themes/'.$theme);

        if (!is_dir($path)) {
            return false;
        }

        return $path;
    }

    /**
     * @throws \Exception
     */
    protected static function setup()
    {
        if (static::$definition === null) {
            static::$definition = require __DIR__.'/map.php';
        }

        if (static::$script_globals === null) {
            static::$script_globals = static::buildScriptGlobals();
        }
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected static function buildScriptGlobals()
    {
        $script_globals = [];

        // Step.1
        // blog admin
        foreach (static::$definition['blog_admin_scripts'] as $path => $globals) {
            if (!is_array($globals)) {
                throw new \Exception('Miss configuration.');
            }

            $script_globals['wp-admin/'.$path] = $globals ?: [];
        }

        // Step.2
        // site admin
        foreach (static::$definition['site_admin_scripts'] as $path => $additional_globals) {
            if (!is_array($additional_globals)) {
                throw new \Exception('Miss configuration.');
            }

            $globals = Arr::get(static::$definition['blog_admin_scripts'], $path, []);

            $script_globals['wp-admin/network/'.$path] = array_merge($globals, $additional_globals);
        }

        return $script_globals;
    }
}
