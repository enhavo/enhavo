<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Filter;

class MatchQuery implements QueryInterface
{
    public const OPERATOR_EQUAL = '=';
    public const OPERATOR_GREATER = '>';
    public const OPERATOR_LESS = '<';
    public const OPERATOR_GREATER_EQUAL = '>=';
    public const OPERATOR_LESS_EQUAL = '<=';
    public const OPERATOR_NOT = '!=';

    /**
     * @var bool
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
