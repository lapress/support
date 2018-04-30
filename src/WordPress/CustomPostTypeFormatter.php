<?php

namespace LaPress\Support\WordPress;

/**
 * @author    Sebastian Szczepański
 * @copyright ably
 */
class CustomPostTypeFormatter
{
    /**
     * @param string $model
     * @return array
     */
    public static function get(string $model): array
    {
        /** @var PostModel $model */
        $model = app($model);

        return [
            'post_type_name' => $model->getPostType(),
            'slug'           => $model->getSlug(),
            'singular'       => trans('labels.post_types.'.$model->getPostType().'.singular'),
            'plural'         => trans('labels.post_types.'.$model->getPostType().'.plural'),
            'public'         => $model->isPostTypePublic(),
        ];
    }

    /**
     * @param string $model
     * @return array
     */
    public static function getOptions(string $model): array
    {
        /** @var PostModel $model */
        $model = app($model);

        return [
            'supports'   => $model->getSupportedFields(),
            'taxonomies' => $model->getSupportedTaxonomies(),
        ];
    }
}