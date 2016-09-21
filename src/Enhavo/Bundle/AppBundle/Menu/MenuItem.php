<?php
/**
 * AbstractMenuItem.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Knp\Menu\MenuItem as KnpMenuItem;

class MenuItem extends KnpMenuItem implements MenuItemInterface
{
    /**
     * @var string
     */
    private $icon;

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }
}