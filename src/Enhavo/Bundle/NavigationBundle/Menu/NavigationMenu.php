<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
use Enhavo\Bundle\AppBundle\Menu\Type\LinkMenuType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationMenu extends AbstractMenuType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'icon' => 'layers',
            'label' => 'navigation.label.navigation',
            'translation_domain' => 'EnhavoNavigationBundle',
            'route' => 'enhavo_navigation_admin_navigation_index',
            'permission' => 'ROLE_ENHAVO_NAVIGATION_NAVIGATION_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'navigation';
    }

    public static function getParentType(): ?string
    {
        return LinkMenuType::class;
    }
}
