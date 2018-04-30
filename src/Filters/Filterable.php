<?php
namespace LaPress\Support\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
trait Filterable
{
    /**
     * Filter a result set.
     *
     * @param  Builder      $query
     * @param  QueryFilters $filters
     * @return Builder
     */
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
}