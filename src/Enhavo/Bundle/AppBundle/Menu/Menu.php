<?php
/**
 * AbstractMenuItem.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Symfony\Component\OptionsResolver\OptionsResolver;

class Menu
{
    /**
     * @var MenuInterface
     */
    private $menu;

    /**
     * @var array
     */
    private $options;

    /**
     * Menu constructor.
     * @param MenuInterface $menu
     * @param $options
     */
    public function __construct(MenuInterface $menu, $options)
    {
        $this->menu = $menu;
        $resolver = new OptionsResolver();
        $menu->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function getPermission()
    {
        return $this->menu->getPermission($this->options);
    }

    public function isHidden()
    {
        return $this->menu->isHidden($this->options);
    }

    public function isActive()
    {
        return $this->menu->isActive($this->options);
    }

    public function createViewData()
    {
        return $this->menu->createViewData($this->options);
    }
}