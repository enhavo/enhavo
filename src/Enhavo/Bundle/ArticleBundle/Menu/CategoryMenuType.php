<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ArticleBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
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
            'permission' => 'ROLE_ENHAVO_ARTICLE_CATEGORY_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'article_category';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
