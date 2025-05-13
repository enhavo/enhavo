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
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property BatchTypeInterface   $type
 * @property BatchTypeInterface[] $parents
 */
class Batch extends AbstractContainerType
{
    public function execute(array $ids, EntityRepository $repository, Data $data, Context $context): void
    {
        $this->type->execute($this->options, $ids, $repository, $data, $context);
    }

    public function createViewData(): array
    {
        $data = new Data();
        $data->set('key', $this->key);
        foreach ($this->parents as $parent) {
            $parent->createViewData($this->options, $data);
        }
        $this->type->createViewData($this->options, $data);

        return $data->normalize();
    }

    public function getPermission(EntityRepository $repository): mixed
    {
        return $this->type->getPermission($this->options, $repository);
    }

    public function isEnabled(): bool
    {
        return $this->type->isEnabled($this->options);
    }
}
