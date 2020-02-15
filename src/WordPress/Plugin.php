<?php

namespace LaPress\Support\WordPress;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class Plugin
{
    /**
     * @param string $plugin
     * @return string
     */
    public static function path(string $plugin)
    {
        return storage_path('content/plugins/'.$plugin);
    }

    /**
     * @param string $plugin
     * @return bool
     */
    public static function exists(string $plugin)
    {
        return file_exists(static::path($plugin));
    }
}
