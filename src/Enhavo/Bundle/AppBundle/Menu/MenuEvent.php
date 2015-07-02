<?php
/**
 * MenuEvent.php
 *
 * @since 08/05/15
 * @author gseidel
 */

namespace enhavo\AdminBundle\Menu;

use Symfony\Component\EventDispatcher\Event;
use Knp\Menu\ItemInterface;

class MenuEvent extends Event
{
    private $menu;

    /**
     * @return ItemInterface
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @param ItemInterface $menu
     */
    public function setMenu(ItemInterface $menu)
    {
        $this->menu = $menu;
    }


}