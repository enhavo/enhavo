<?php
/**
 * AbstractMenuBuilder.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Bundle\AppBundle\Type\AbstractType;

abstract class AbstractMenu extends AbstractType implements MenuInterface
{
    public function getPermission(array $options)
    {
        return $this->getOption('role', $options, null);
    }

    public function isHidden(array $options)
    {
        return $this->getOption('hidden', $options, null);
    }
}