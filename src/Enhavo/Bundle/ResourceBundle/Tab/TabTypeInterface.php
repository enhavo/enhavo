<?php
/**
 * BatchInterface.php
 *
 * @since 04/07/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Tab;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Input\InputInterface;
use Enhavo\Component\Type\TypeInterface;

interface TabTypeInterface extends TypeInterface
{
    public function createViewData(array $options, Data $data, InputInterface $input): void;

    public function getPermission(array $options, InputInterface $input): mixed;

    public function isEnabled(array $options, InputInterface $input): bool;
}
