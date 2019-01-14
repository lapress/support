<?php

namespace LaPress\Support\WordPress;

use Illuminate\Http\Request;
use App\Models\Post;

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
        $postType = ucfirst($this->getPostType());
        $class = 'App\\Models\\'.$postType;

        if (class_exists($class)) {
            return $class;
        }

        $class = 'LaPress\\Models\\'.$postType;

        if (class_exists($class)) {
            return $class;
        }

        return Post::class;
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
