<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Batch;

use Doctrine\ORM\EntityRepository;
use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Component\Type\TypeInterface;

interface BatchTypeInterface extends TypeInterface
{
    public function execute(array $options, array $ids, EntityRepository $repository, Data $data, Context $context): void;

    public function createViewData(array $options, Data $data): void;

    public function getPermission(array $options, EntityRepository $repository): mixed;

    public function isEnabled(array $options): bool;
}
