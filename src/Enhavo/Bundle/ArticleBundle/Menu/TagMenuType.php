<?php

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagMenuType extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'label_outline',
            'label' => 'article.label.tag',
            'translation_domain' => 'EnhavoArticleBundle',
            'route' => 'enhavo_article_tag_index',
            'role' => 'ROLE_ENHAVO_ARTICLE_TAG_INDEX'
        ]);
    }

    public function getType()
    {
        return 'article_tag';
    }
}
