<?php

namespace LaPress\Support\WordPress;

use Illuminate\Http\Request;
use App\Post;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class PostModelResolver
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    public function resolve()
    {
        $class = 'App\\'.ucfirst($this->getPostType());

        if (class_exists($class)) {
            return $class;
        }

        return Post::class;
    }

    /**
     * @return string
     */
    public function getPostType()
    {
        $uri = str_after($this->request->getRequestUri(), '/');
        $uri = str_before($uri, '/');
        $uri = str_before($uri, '?');

        return str_singular($uri);
    }
}