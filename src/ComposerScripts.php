<?php

namespace LaPress\Support;

use LaPress\Support\Composer\RenameHelper;
use Composer\Script\Event;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class ComposerScripts
{
    const SCRIPTS = [
        RenameHelper::class,
    ];

    public static function postAutoload(Event $event)
    {
        foreach (static::SCRIPTS as $script) {
           $script($event);
        }
    }
}
