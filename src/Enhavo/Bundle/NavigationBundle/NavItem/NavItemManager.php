<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\NavigationBundle\NavItem;

use Enhavo\Component\Type\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class NavItemManager
{
    use ContainerAwareTrait;

    /**
     * @var NavItem[]
     */
    private $items = [];

    public function __construct(Factory $factory, $configurations)
    {
        foreach ($configurations as $name => $options) {
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

    public function getFactory($name)
    {
        $navItem = $this->getNavItem($name);
        $factoryClass = $navItem->getFactory();
        if ($factoryClass) {
            if ($this->container->has($factoryClass)) {
                $factory = $this->container->get($factoryClass);
            } else {
                $factory = new $factoryClass($navItem->getModel());
                if ($factory instanceof ContainerAwareInterface) {
                    $factory->setContainer($this->container);
                }
            }

            return $factory;
        }

        return null;
    }
}
