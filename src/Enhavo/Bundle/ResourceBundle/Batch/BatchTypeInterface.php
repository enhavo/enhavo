<?php
/**
 * BatchInterface.php
 *
 * @since 04/07/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Batch;

use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepositoryInterface;
use Enhavo\Component\Type\TypeInterface;

interface BatchTypeInterface extends TypeInterface
{
    public function execute(array $options, array $ids, EntityRepositoryInterface $repository, Data $data, Context $context): void;

    public function createViewData(array $options, Data $data): void;

    public function getPermission(array $options, EntityRepositoryInterface $repository): mixed;

    public function isEnabled(array $options): bool;
}
