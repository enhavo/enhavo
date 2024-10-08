<?php
/**
 * BatchInterface.php
 *
 * @since 04/07/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Action;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\TypeInterface;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;

interface ActionTypeInterface extends TypeInterface
{
    public function createViewData(array $options, Data $data, object $resource = null): void;

    public function getPermission(array $options, ResourceInterface $resource = null): mixed;

    public function isEnabled(array $options, ResourceInterface $resource = null): bool;

    public function getLabel(array $options): string;
}
