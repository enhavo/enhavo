<?php
/**
 * MenuInterface.php
 *
 * @since 16/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Knp\Menu\ItemInterface;

interface MenuItemInterface extends ItemInterface
{
    public function getIcon();

    public function setIcon($icon);
}