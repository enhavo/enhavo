<?php

namespace Enhavo\Bundle\ResourceBundle\Batch;

use Enhavo\Bundle\ApiBundle\Endpoint\Context;
use Enhavo\Bundle\ResourceBundle\Repository\EntityRepositoryInterface;
use Enhavo\Component\Type\AbstractContainerType;
use Enhavo\Bundle\ApiBundle\Data\Data;

/**
 * @property BatchTypeInterface $type
 * @property BatchTypeInterface[] $parents
 */
class Batch extends AbstractContainerType
{
    public function execute($ids, EntityRepositoryInterface $repository, Data $data, Context $context): void
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

    public function getPermission(EntityRepositoryInterface $repository): mixed
    {
        return $this->type->getPermission($this->options, $repository);
    }

    public function isEnabled(): bool
    {
        return $this->type->isEnabled($this->options);
    }
}
