<?php
/**
 * ToolbarWidgetTypeInterface.php
 *
 * @since 11/02/20
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Toolbar;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\TypeInterface;

interface ToolbarWidgetTypeInterface extends TypeInterface
{
    public function createViewData(array $options, Data $data): void;

    public function getPermission(array $options): mixed;

    public function isEnabled(array $options): bool;
}
