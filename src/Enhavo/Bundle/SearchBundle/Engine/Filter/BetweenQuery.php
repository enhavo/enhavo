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

class BetweenQuery implements QueryInterface
{
    public const OPERATOR_THAN = '<>';
    public const OPERATOR_EQUAL_THAN = '=';

    /**
     * @var string
     */
    private $operatorFrom;

    /**
     * @var string
     */
    private $operatorTo;

    private $from;

    private $to;

    /**
     * BetweenQuery constructor.
     *
     * @param null   $from
     * @param null   $to
     * @param string $operatorFrom
     * @param string $operatorTo
     */
    public function __construct($from = null, $to = null, $operatorFrom = self::OPERATOR_THAN, $operatorTo = self::OPERATOR_THAN)
    {
        $this->from = $from;
        $this->to = $to;
        $this->operatorFrom = $operatorFrom;
        $this->operatorTo = $operatorTo;
    }

    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $operatorFrom
     */
    public function setFrom($from, $operatorFrom = self::OPERATOR_EQUAL_THAN)
    {
        $this->operatorFrom = $operatorFrom;
        $this->from = $from;
    }

    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param string $operatorTo
     */
    public function setTo($to, $operatorTo = self::OPERATOR_EQUAL_THAN)
    {
        $this->operatorTo = $operatorTo;
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getOperatorFrom()
    {
        return $this->operatorFrom;
    }

    /**
     * @return string
     */
    public function getOperatorTo()
    {
        return $this->operatorTo;
    }

    /**
     * @param string $operatorFrom
     */
    public function setOperatorFrom($operatorFrom)
    {
        $this->operatorFrom = $operatorFrom;
    }

    /**
     * @param string $operatorTo
     */
    public function setOperatorTo($operatorTo)
    {
        $this->operatorTo = $operatorTo;
    }
}
