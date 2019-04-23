<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-02-17
 * Time: 22:32
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Bundle\AppBundle\Type\TypeFactory;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MenuManager
{
    /**
     * @var TypeFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $configuration;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $checker;

    public function __construct(TypeFactory $factory, AuthorizationCheckerInterface $checker, $configuration)
    {
        $this->factory = $factory;
        $this->configuration = $configuration;
        $this->checker = $checker;
    }

    public function createMenuViewData()
    {
        $data = [];
        $menus = $this->getMenuItems();
        foreach($menus as $menu) {
            $data[] = $menu->createViewData();
        }
        return $data;
    }

    /**
     * @return Menu[]
     */
    public function getMenuItems()
    {
        if(!$this->configuration) {
            return [];
        }
        return $this->getMenuItemsByConfiguration($this->configuration);
    }

    /**
     * @param $configuration
     * @return Menu[]
     */
    public function getMenuItemsByConfiguration($configuration)
    {
        $menus = [];
        foreach($configuration as $name => $options) {
            /** @var Menu $menu */
            $menu = $this->factory->create($options);

            if($menu->isHidden()) {
                continue;
            }

            if($menu->getPermission() !== null && !$this->checker->isGranted($menu->getPermission())) {
                continue;
            }

            $menus[] = $menu;
        }

        return $menus;
    }
}