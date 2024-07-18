<?php
/**
 * BatchInterface.php
 *
 * @since 04/07/15
 * @author gseidel
 */

namespace Enhavo\Bundle\ResourceBundle\Batch;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Component\Type\TypeInterface;

interface BatchTypeInterface extends TypeInterface
{
    public function execute(array $options, array $ids, EntityRepository $repository, Data $data, Context $context): void;

    public function createViewData(array $options, Data $data): void;

    public function getPermission(array $options, EntityRepository $repository): mixed;

    public function isEnabled(array $options): bool;
}
