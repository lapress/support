<?php

namespace LaPress\Support\WordPress\Admin;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
interface Page
{
    /**
     * @return Page
     */
    public function register();

    /**
     * @return Page
     */
    public function create();
}