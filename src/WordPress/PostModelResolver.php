<?php

namespace LaPress\Support\WordPress;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;

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
        $uri = str_replace('/api', '', $this->request->getRequestUri());
        $uri = str_after($uri, '/');
        $uri = str_before($uri, '/');
        $uri = str_before($uri, '?');

        $map = Cache::rememberForever('post.types.map', function () use ($uri){
            return collect(config('wordpress.posts.types'))->mapWithKeys(function ($class, $key) use($uri) {
                $class = new $class;
                return [$class->getSlug() => $key];
            })->toArray();
        });

        return $map[$uri] ?? str_singular($uri);
    }
}
