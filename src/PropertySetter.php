<?php

namespace LaPress\Support;

use Illuminate\Support\Str;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PropertySetter
{
    /**
     * @param object $object
     * @param array $data
     * @return object
     */
    public static function set($object, array $data = [])
    {
        $instance = new static();

        foreach ($data as $key => $value) {
            $methodName = $instance->getMethodName($key);

            if (!method_exists($object, $methodName)) {
                continue;
            }

            $object->{$methodName}($value);
        }

        return $object;
    }

    /**
     * @param $key
     * @return string
     */
    public function getMethodName($key): string
    {
        return sprintf('set%s', Str::camel($key));
    }
}
