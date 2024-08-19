<?php
/**
 * MenuBuilderInterface.php
 *
 * @since 20/09/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Menu;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\TypeInterface;

interface MenuTypeInterface extends TypeInterface
{
    public function getPermission(array $options): mixed;

    public function isEnabled(array $options): bool;

    public function createViewData(array $options, Data $data): void;
}
