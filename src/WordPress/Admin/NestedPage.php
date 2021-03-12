<?php

namespace LaPress\Support\WordPress\Admin;

use LaPress\Database\Models\Option;
use Illuminate\Support\Str;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class NestedPage implements Page
{
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $parentKey;

    public function __construct(string $parentKey, string $key)
    {
        $this->parentKey = $parentKey;
        $this->key = Str::slug($key);

        add_action('admin_init', [$this, 'build']);
    }

    public function build()
    {
        register_setting("{$this->key}", "{$this->key}");
    }

    /**
     * @return Page
     */
    public function register()
    {
        add_submenu_page(
            $this->parentKey,
            trans('labels.admin.pages.'.$this->key),
            trans('labels.admin.pages.'.$this->key),
            'manage_options',
            $this->key,
            [$this, 'getContent']
        );
    }

    /**
     * @param string|null $parentKey
     * @param string|null $key
     * @return Page
     */
    public function create(string $parentKey = null, string $key = null)
    {
        if (self::isStatic()) {
            return static::make($parentKey, $key)
                         ->create();
        }


        return $this->register();
    }

    public function getContent()
    {
        $path = $this->getFilePath($this->key);
        $styles = $this->getFilePath('__styles');
        $scripts = $this->getFilePath('__scripts');

        if (file_exists($styles)) {
            include $styles;
        }

        if (file_exists($path)) {
            include $path;
        }

        if (file_exists($scripts)) {
            include $scripts;
        }

        return '';
    }

    public static function make(string $parentKey, string $key)
    {
        return (new static($parentKey, $key))->handle();
    }

    private static function isStatic(): bool
    {
        $backtrace = debug_backtrace();

        // The 0th call is to _isStatic(), so we need to check the next
        // call down the stack.
        return $backtrace[1]['type'] == '::';
    }

    private function handle()
    {
        $request = app('request');
        $keyName = '__'.$this->key;
        if ($this->saving()) {
            foreach ($request->get($keyName) as $k => $data) {
                Option::set($keyName.':'.$k, $data);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function saving(): bool
    {
        $request = app('request');
        $keyName = '__'.$this->key;
        $key = $request->get('option_key');

        return $request->method() == 'POST' && $key == $keyName;
    }

    /**
     * @return string
     */
    public function getFilePath($name): string
    {
        return get_template_directory().'/inc/pages/'.$name.'.php';
    }
}
