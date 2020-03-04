<?php

namespace LaPress\Support\WordPress\Admin;

use Illuminate\Support\Str;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class TopLevelPage implements Page
{
    /**
     * @var string
     */
    private $key;
    private $pages;

    /**
     * @param string|null $key
     */
    public function __construct(string $key)
    {
        $this->key = Str::slug($key);
    }

    public function register()
    {
        add_menu_page(
            trans('labels.admin.pages.'.$this->key),
            trans('labels.admin.pages.'.$this->key),
            'manage_options',
            $this->key,
            '',
            $this->getIcon(),
            $this->getPosition()
        );

        $this->addSubPages();
    }

    private function getPosition()
    {
        return 1;
    }


    private function getIcon()
    {
    }

    public function setPages(array $pages = [])
    {
        $this->pages = $pages;

        return $this;
    }

    public function getPages()
    {
        return array_merge([$this->key], $this->pages);
    }

    public static function create(string $key = null, $pages = [])
    {
        return static::make($key, $pages)
                     ->_create();

    }

    public function _create()
    {
        add_action('admin_menu', [$this, 'register']);

        return $this;
    }

    public static function make(string $key = null, $pages = [])
    {
        return (new static($key))->setPages($pages);
    }

    private static function isStatic(): bool
    {
        $backtrace = debug_backtrace();

        // The 0th call is to _isStatic(), so we need to check the next
        // call down the stack.
        return $backtrace[1]['type'] == '::';
    }

    public function addSubPages()
    {
        foreach ($this->getPages() as $page) {
            NestedPage::make($this->key, $page)->create();
        }
    }
}
