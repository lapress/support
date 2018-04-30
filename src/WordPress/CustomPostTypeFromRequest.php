<?php

namespace LaPress\Support\WordPress;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class CustomPostTypeFromRequest
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request ?: app('request');
    }

    /**
     * @param Request|null $request
     * @return string
     */
    public static function get(Request $request = null): string
    {
        $instance = new static($request);

        return $instance->getPostType();
    }

    /**
     * @return string
     */
    public function getPostType(): string
    {
        $uri = str_after($this->request->getRequestUri(), '/');
        $uri = str_before($uri, '/');
        $uri = str_before($uri, '?');

        return str_singular($uri);
    }
}