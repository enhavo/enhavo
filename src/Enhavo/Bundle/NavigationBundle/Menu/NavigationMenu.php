<?php
/**
 * NavigationMenuBuilder.php
 *
 * @since 03/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\NavigationBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\AbstractMenuType;
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
            'role' => 'ROLE_ENHAVO_NAVIGATION_NAVIGATION_INDEX',
        ]);
    }

    public static function getName(): ?string
    {
        return 'navigation';
    }
}
