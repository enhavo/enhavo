<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-17
 * Time: 22:32
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Component\Type\FactoryInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuManager
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
        private readonly FactoryInterface $menuFactory,
    )
    {
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

            if ($menu->getPermission() !== null && !$this->checker->isGranted($menu->getPermission())) {
                continue;
            }

            $items[$key] = $menu;
        }

        return $items;
    }
}
