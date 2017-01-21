<?php
/**
 * FilterQuery.php
 *
 * @since 19/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Filter;


class FilterQuery
{
    const OPERATOR_EQUALS = '=';
    const OPERATOR_LIKE = 'like';

    const ORDER_ASC = 'asc';
    const ORDER_DESC = 'desc';

    /**
     * @var array
     */
    private $where = [];

    /**
     * @var array
     */
    private $orderBy = [];

    public function addOrderBy($property, $order)
    {
        $this->orderBy[] = [
            'property' => $property,
            'order' => $order
        ];
    }

    public function addWhere($property, $operator, $value, $joinProperty = null)
    {
        $this->where[] = [
            'property' => $property,
            'operator' => $operator,
            'value' => $value,
            'joinProperty' => $joinProperty
        ];
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }
}