<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\ResourceBundle\Filter;

class FilterQueryOr
{
    /**
     * @var array
     */
    private $where = [];

    public function addWhere($property, $operator, $value, $joinProperty = null)
    {
        if (null === $joinProperty) {
            $joinProperty = [];
        } elseif (!is_array($joinProperty)) {
            $joinProperty = [$joinProperty];
        }
        $this->where[] = [
            'property' => $property,
            'operator' => $operator,
            'value' => $value,
            'joinProperty' => $joinProperty,
        ];

        return $this;
    }

    public function removeWhere($property, $operator, $value, $joinProperty = null)
    {
        if (!$property && !$operator && !$value && !$joinProperty) {
            return $this;
        }
        foreach ($this->where as $index => $where) {
            if ($property && $where['property'] !== $property) {
                continue;
            }
            if ($operator && $where['operator'] !== $operator) {
                continue;
            }
            if ($value && $where['value'] !== $value) {
                continue;
            }
            if ($joinProperty && $where['joinProperty'] !== $joinProperty) {
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
