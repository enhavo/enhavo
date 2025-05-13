<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
        private readonly FactoryInterface $menuFactory,
    ) {
    }

    /**
     * @return Menu[]
     */
    public function getMenuItems(array $configuration): array
    {
        $items = [];
        foreach ($configuration as $key => $options) {
            /** @var Menu $menu */
            $menu = $this->menuFactory->create($options, $key);

            if (!$menu->isEnabled()) {
                continue;
            }

            if (null !== $menu->getPermission() && !$this->checker->isGranted($menu->getPermission())) {
                continue;
            }

            $items[$key] = $menu;
        }

        return $items;
    }
}
