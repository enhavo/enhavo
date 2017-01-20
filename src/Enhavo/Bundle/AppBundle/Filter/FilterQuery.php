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

    private $where = [];

    public function addWhere($property, $operator, $value)
    {
        $this->where[] = [
            'property' => $property,
            'operator' => $operator,
            'value' => $value
        ];
    }

    public function getWhere()
    {
        return $this->where;
    }
}