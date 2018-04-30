<?php

namespace LaPress\Support;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class ThemeBladeDirectory
{
    /**
     * @return string
     */
    public static function get(): string
    {
        return storage_path().'/../theme/'.config('wordpress.theme.views');
    }
}