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
    const OPERATOR_GREATER = '>';
    const OPERATOR_LESS = '<';
    const OPERATOR_GREATER_EQUAL = '>=';
    const OPERATOR_LESS_EQUAL = '<=';
    const OPERATOR_NOT = '!=';
    const OPERATOR_LIKE = 'like';
    const OPERATOR_START_LIKE = 'start_like';
    const OPERATOR_END_LIKE = 'end_like';

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

    public function removeOrderBy($property, $order)
    {
        if(!$property && !$order){
            return;
        }
        foreach ($this->orderBy as $index => $orderBy){
            if($property && $orderBy['property'] !== $property){
                continue;
            }
            if($order && $orderBy['operator'] !== $order){
                continue;
            }
            unset($this->orderBy[$index]);
        }
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

    public function removeWhere($property, $operator, $value, $joinProperty = null)
    {
        if(!$property && !$operator && !$value && !$joinProperty){
            return;
        }
        foreach ($this->where as $index => $where){
            if($property && $where['property'] !== $property){
                continue;
            }
            if($operator && $where['operator'] !== $operator){
                continue;
            }
            if($value && $where['value'] !== $value){
                continue;
            }
            if($joinProperty && $where['joinProperty'] !== $joinProperty){
                continue;
            }
            unset($this->where[$index]);
        }
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