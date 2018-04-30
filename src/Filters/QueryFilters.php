<?php

namespace LaPress\Support\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
abstract class QueryFilters
{
    /**
     * The request object.
     *
     * @var Request
     */
    protected $request;
    /**
     * The builder instance.
     *
     * @var Builder
     */
    protected $builder;
    /**
     * Create a new QueryFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
     * Apply the filters to the builder.
     *
     * @param  Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder)
    {
        $this->builder = $builder;
        foreach ($this->filters() as $name => $value) {
            if (! method_exists($this, $name)) {
                continue;
            }
            if (trim($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }
        return $this->builder;
    }
    /**
     * Get all request filters data.
     *
     * @return array
     */
    public function filters()
    {
        return $this->request->all();
    }

    /**
     * @param $key
     * @return array|string
     */
    public function get($key)
    {
        return $this->request->input($key);
    }

    public function getRequest()
    {
        return $this->request;
    }


    /**
     * @param int $toSkip
     * @return mixed
     */
    public function skip(int $toSkip = 0)
    {
        return $this->builder->offset($toSkip);
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function take(int $limit)
    {
        return $this->builder->limit($limit);
    }
}