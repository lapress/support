<?php

namespace LaPress\Support\WordPress;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Theme
{
    /**
     * @param string|null $theme
     * @return string
     */
    public static function path(string $theme = null)
    {
        $theme = $theme ?: config('wordpress.theme.active');
        
        return themes($theme);
    }

    /**
     * @param string|null $theme
     * @return bool
     */
    public static function exists(string $theme = null)
    {
        return file_exists(static::style($theme));
    }

    /**
     * @param string $path
     * @param null   $theme
     * @return string
     */
    public static function asset(string $path, $theme = null)
    {
//        dump(static::path($theme).'/public/'.$path);die();
        return static::path($theme).'/public/'.$path;
    }

    /**
     * @param string|null $theme
     * @return string
     */
    public static function style(string $theme = null)
    {
        return static::asset('style.css', $theme);
    }

    /**
     * @return string
     */
    public static function get()
    {
        return config('wordpress.theme.active') ?: 'theme';
    }

    /**
     * @param string $path
     * @return \Illuminate\Support\HtmlString|string
     * @throws \Exception
     */
    public static function mix(string $path)
    {
        return mix($path, sprintf('/%s/dist', static::get()));
    }
}
