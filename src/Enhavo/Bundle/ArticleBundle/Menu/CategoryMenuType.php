<?php

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'bookmark',
            'label' => 'article.label.category',
            'translation_domain' => 'EnhavoArticleBundle',
            'route' => 'enhavo_article_admin_category_index',
            'role' => 'ROLE_ENHAVO_ARTICLE_CATEGORY_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'article_category';
    }
}
