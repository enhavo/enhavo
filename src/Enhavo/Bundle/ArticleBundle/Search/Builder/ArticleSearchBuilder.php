<?php
/**
 * Created by PhpStorm.
 * User: jhelbing
 * Date: 23.01.16
 * Time: 15:36
 */

class ArticleSearchBuilder
{
    public function build($builder) {
        $builder->add('title', 'text', array(
            'label' => 'form.label.title',
            'translation_domain' => 'EnhavoAppBundle'
        ));

        $builder->add('slug', 'text', array(
            'label' => 'form.label.slug',
            'translation_domain' => 'EnhavoAppBundle'
        ));
    }
}