<?php

namespace Enhavo\Bundle\AppBundle\Filter;

class FilterQueryOr
{
    /**
     * @var array
     */
    private $where = [];

    public function addWhere($property, $operator, $value, $joinProperty = null)
    {
        $this->where[] = [
            'property' => $property,
            'operator' => $operator,
            'value' => $value,
            'joinProperty' => $joinProperty
        ];

        return $this;
    }

    public function removeWhere($property, $operator, $value, $joinProperty = null)
    {
        if(!$property && !$operator && !$value && !$joinProperty){
            return $this;
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

        return $this;
    }

    public function getWhere()
    {
        return $this->where;
    }
}
