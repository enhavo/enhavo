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
use Enhavo\Bundle\ResourceBundle\Batch\Type\BaseBatchType;
use Enhavo\Component\Type\AbstractType;

/**
 * @property BatchTypeInterface $parent
 */
abstract class AbstractBatchType extends AbstractType implements BatchTypeInterface
{
    public function execute(array $options, array $ids, EntityRepository $repository, Data $data, Context $context): void
    {
        $this->parent->execute($options, $ids, $repository, $data, $context);
    }

    public function createViewData(array $options, Data $data): void
    {
    }

    public static function getParentType(): ?string
    {
        return BaseBatchType::class;
    }

    public function getPermission(array $options, EntityRepository $repository): mixed
    {
        return $this->parent->getPermission($options, $repository);
    }

    public function isEnabled(array $options): bool
    {
        return $this->parent->isEnabled($options);
    }
}
