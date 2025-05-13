<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\PageBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author gseidel
 */
class PageMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'pages',
            'label' => 'page.label.page',
            'translation_domain' => 'EnhavoPageBundle',
            'route' => 'enhavo_page_admin_page_index',
            'permission' => 'ROLE_ENHAVO_PAGE_PAGE_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'page';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
