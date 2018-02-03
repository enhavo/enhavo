<?php
/**
 * NavigationMenuBuilder.php
 *
 * @since 03/02/18
 * @author gseidel
 */

namespace Enhavo\Bundle\NavigationBundle\Menu;

use Enhavo\Bundle\AppBundle\Menu\Builder\BaseMenuBuilder;

class NavigationMenuBuilder extends BaseMenuBuilder
{
    public function createMenu(array $options)
    {
        $this->setOption('icon', $options, 'map');
        $this->setOption('label', $options, 'navigation.label.navigation');
        $this->setOption('translationDomain', $options, 'EnhavoNavigationBundle');
        $this->setOption('route', $options, 'enhavo_navigation_navigation_index');
        $this->setOption('role', $options, 'ROLE_ENHAVO_NAVIGATION_NAVIGATION_INDEX');
        return parent::createMenu($options);
    }

    public function getType()
    {
        return 'navigation';
    }
}