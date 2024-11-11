<?php

namespace Enhavo\Bundle\ResourceBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Column\Type\BaseColumnType;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Component\Type\AbstractType;

/**
 * @property ColumnTypeInterface $parent
 */
abstract class AbstractColumnType extends AbstractType implements ColumnTypeInterface
{
    public function createResourceViewData(array $options, object $resource, Data $data): void
    {

    }

    public function createColumnViewData(array $options, Data $data): void
    {

    }

    public function buildSortingQuery($options, FilterQuery $query, string $direction): void
    {
        $this->parent->buildSortingQuery($options, $query, $direction);
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
