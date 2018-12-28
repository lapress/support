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
        return themes(
            config('wordpress.theme.active').'/'.config('wordpress.theme.views')
        );
    }
}
