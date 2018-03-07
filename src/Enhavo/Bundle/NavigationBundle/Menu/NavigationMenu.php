<?php
/**
 * NavigationMenuBuilder.php
 *
 * @since 03/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\NavigationBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Menu\BaseMenu;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavigationMenu extends BaseMenu
{
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'icon' => 'map',
            'label' => 'navigation.label.navigation',
            'translationDomain' => 'EnhavoNavigationBundle',
            'route' => 'enhavo_navigation_navigation_index',
            'role' => 'ROLE_ENHAVO_NAVIGATION_NAVIGATION_INDEX',
        ]);
    }

    public function getType()
    {
        return 'navigation';
    }
}