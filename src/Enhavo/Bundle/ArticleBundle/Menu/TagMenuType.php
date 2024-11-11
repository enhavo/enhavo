<?php

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagMenuType extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'icon' => 'label_outline',
            'label' => 'article.label.tag',
            'translation_domain' => 'EnhavoArticleBundle',
            'route' => 'enhavo_article_admin_tag_index',
            'role' => 'ROLE_ENHAVO_ARTICLE_TAG_INDEX'
        ]);
    }

    public static function getName(): ?string
    {
        return 'article_tag';
    }
}
