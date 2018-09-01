<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29.08.18
 * Time: 18:48
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Filter;


class MatchQuery implements QueryInterface
{
    const OPERATOR_EQUAL = '=';
    const OPERATOR_GREATER = '>';
    const OPERATOR_LESS = '<';
    const OPERATOR_GREATER_EQUAL = '>=';
    const OPERATOR_LESS_EQUAL = '<=';
    const OPERATOR_NOT = '!=';

    /**
     * @var boolean
     */
    private $value;

    /**
     * @var string
     */
    private $operator;

    public function __construct($value = null, $operator = self::OPERATOR_EQUAL)
    {
        $this->value = $value;
        $this->operator = $operator;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param bool $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }
}