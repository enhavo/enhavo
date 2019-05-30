<?php

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryMenuType extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'icon' => 'bookmark',
            'label' => 'article.label.category',
            'translation_domain' => 'EnhavoArticleBundle',
            'route' => 'enhavo_article_category_index',
            'role' => 'ROLE_ENHAVO_ARTICLE_CATEGORY_INDEX'
        ]);
    }

    public function getType()
    {
        return 'article_category';
    }
}
