<?php

namespace Enhavo\Bundle\ResourceBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\Type\BaseColumnType;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Component\Type\AbstractType;

/**
 * @property ColumnTypeInterface $parent
 */
abstract class AbstractColumnType extends AbstractType implements ColumnTypeInterface
{
    public function createResourceViewData(array $options, ResourceInterface $resource, Data $data): void
    {

    }

    public function createColumnViewData(array $options, Data $data): void
    {

    }

    public function getPermission(array $options): mixed
    {
        return $this->parent->getPermission($options);
    }

    public function isEnabled(array $options): bool
    {
        return $this->parent->isEnabled($options);
    }

    public static function getParentType(): ?string
    {
        return BaseColumnType::class;
    }
}
