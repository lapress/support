<?php

namespace LaPress\Support\Composer;

use Composer\Script\Event;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class RenameHelper
{
    public function handle(Event $event)
    {
        $vendorDir   = $event->getComposer()->getConfig()->get('vendor-dir');
        $helpersPath = $vendorDir . '/laravel/framework/src/Illuminate/Foundation/helpers.php';
        if ( ! file_exists($helpersPath)) {
            return;
        }
        $content = file_get_contents($helpersPath);
        $content = str_replace("function_exists('__')", "function_exists('___')", $content);
        $content = str_replace('function __(', 'function ___(', $content);
        file_put_contents($helpersPath, $content);
    }
}
