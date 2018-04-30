<?php

namespace LaPress\Support\WordPress;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class CustomPostTypeTaxonomyFormatter
{
    /**
     * @param string $taxonomy
     * @param array  $options
     * @return array
     */
    public static function get(string $taxonomy, array $options = []): array
    {
        return array_merge([
            'taxonomy_name' => $taxonomy,
            'singular'      => trans('labels.taxonomies.'.$taxonomy.'.singular'),
            'plural'        => trans('labels.taxonomies.'.$taxonomy.'.plural'),
        ], $options);
    }
}