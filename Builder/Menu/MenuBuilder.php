<?php
/**
 * MenuBuilder.php
 *
 * @since 14/10/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace esperanto\AdminBundle\Builder\Menu;

use esperanto\AdminBundle\Admin\Menu;

class MenuBuilder
{
    /**
     * @var string
     */
    private $routeName;

    /**
     * @var string
     */
    private $iconName;

    /**
     * @var string
     */
    private $label;

    /**
     * @return string
     */
    public function getIconName()
    {
        return $this->iconName;
    }

    /**
     * @param string $iconName
     */
    public function setIconName($iconName)
    {
        $this->iconName = $iconName;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * @param string $routeName
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;
    }

    public function getMenu()
    {
        $menu = new Menu();
        $menu->setRouteName($this->getRouteName());
        $menu->setIconName($this->getIconName());
        $menu->setName($this->getLabel());
        return $menu;
    }
} 