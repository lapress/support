<?php

namespace LaPress\Support;
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
        return array_get(static::scriptGlobals(), $script_path);
    }

    /**
     * @return mixed|void
     */
    public static function activePlugins()
    {
        return get_option('active_plugins');
    }

    /**
     * @param $plugin
     * @return bool|string
     */
    public static function pluginPath($plugin)
    {
        $path = wordpress_path('wp-content/plugins/').$plugin.'/'.$plugin.'.php';

        if (!file_exists($path)) {
            $path = wordpress_path('wp-content/plugins/').$plugin.'.php';

            if (!file_exists($path)) {
                return false;
            }
        }

        return $path;
    }

    /**
     * @return string
     */
    public static function activetheme_path()
    {
        return get_template();
    }

    /**
     * @param $theme
     * @return bool|string
     */
    public static function themePath($theme)
    {
        $path = wordpress_path('wp-content/themes/').$theme;

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

            $globals = array_get(static::$definition['blog_admin_scripts'], $path, []);

            $script_globals['wp-admin/network/'.$path] = array_merge($globals, $additional_globals);
        }

        return $script_globals;
    }
}
