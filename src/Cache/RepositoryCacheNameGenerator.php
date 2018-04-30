<?php

namespace LaPress\Support\Cache;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class RepositoryCacheNameGenerator
{
    const GLUE = ':';

    /**
     * @var string
     */
    protected $base;

    /**
     * @var array
     */
    protected $criteria;

    /**
     * @param       $base
     * @param array $criteria
     */
    public function __construct(string $base, array $criteria = [])
    {
        $this->base = $base;
        $this->criteria = $criteria;
    }

    public function get(): string
    {
        return $this->getBase().$this->getCriteria();
    }

    public function getBase()
    {
        if (!$this->base) {
            return '';
        }

        if (empty($this->criteria)) {
            return $this->base;
        }

        return $this->base.static::GLUE;
    }

    private function getCriteria()
    {
        return collect($this->criteria)->mapWithKeys(function ($value, $key) {
            if (is_array($value)) {
                $value = collect($value)->map(function ($item) {
                    if (is_array($item)) {
                        return collect($item)->implode('|');
                    }

                    return $item;
                })->implode(',');
            }

            return [$key => [$key, $value]];
        })->values()->flatten()->implode(static::GLUE);
    }
}