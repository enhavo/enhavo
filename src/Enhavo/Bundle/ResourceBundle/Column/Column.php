<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2019-03-28
 * Time: 15:31
 */

namespace Enhavo\Bundle\ResourceBundle\Column;

use Enhavo\Bundle\ApiBundle\Data\Data;
use Enhavo\Bundle\ResourceBundle\Filter\FilterQuery;
use Enhavo\Bundle\ResourceBundle\Model\ResourceInterface;
use Enhavo\Component\Type\AbstractContainerType;

/**
 * @property ColumnTypeInterface $type
 * @property ColumnTypeInterface[] $parents
 */
class Column extends AbstractContainerType
{
    const SORTING_DIRECTION_ASC = 'asc';
    const SORTING_DIRECTION_DESC = 'desc';


    public function buildSortingQuery(FilterQuery $query, string $direction): void
    {
        $this->type->buildSortingQuery($this->options, $query, $direction);
    }

    public function createColumnViewData(): array
    {
        $data = new Data();
        $data->set('key', $this->key);

        foreach ($this->parents as $parent) {
            $parent->createColumnViewData($this->options, $data);
        }

        $this->type->createColumnViewData($this->options, $data);

        return $data->normalize();
    }

    public function createResourceViewData(ResourceInterface $resource): array
    {
        $data = new Data();
        $data->set('key', $this->key);

        foreach ($this->parents as $parent) {
            $parent->createResourceViewData($this->options, $resource, $data);
        }

        $this->type->createResourceViewData($this->options, $resource, $data);

        return $data->normalize();
    }

    public function getPermission(): mixed
    {
        return $this->type->getPermission($this->options);
    }

    public function isEnabled(): bool
    {
        return $this->type->isEnabled($this->options);
    }
}
