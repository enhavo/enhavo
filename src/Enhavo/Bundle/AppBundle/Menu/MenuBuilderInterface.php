<?php
/**
 * MenuBuilderInterface.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Bundle\AppBundle\Type\TypeInterface;

interface MenuBuilderInterface extends TypeInterface
{
    public function createMenu(array $options);

    public function isGranted(MenuItemInterface $menuItem);
}