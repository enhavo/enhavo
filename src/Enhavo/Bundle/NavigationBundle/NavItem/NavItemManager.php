<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.08.18
 * Time: 21:56
 */

namespace Enhavo\Bundle\NavigationBundle\NavItem;

use Enhavo\Component\Type\Factory;

class NavItemManager
{
    /**
     * @var NavItem[]
     */
    private $items = [];

    public function __construct(Factory $factory, $configurations)
    {
        foreach($configurations as $name => $options) {
            $this->items[$name] = $factory->create($options);
        }
    }

    public function getNavItems()
    {
        return $this->items;
    }

    public function getNavItem($name)
    {
        return $this->items[$name];
    }
}
