<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29.08.18
 * Time: 18:49
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Filter;


class BetweenQuery implements QueryInterface
{
    const OPERATOR_THAN = '<>';
    const OPERATOR_EQUAL_THAN = '=';

    /**
     * @var string
     */
    private $operatorFrom;

    /**
     * @var string
     */
    private $operatorTo;

    /**
     * @var mixed
     */
    private $from;

    /**
     * @var mixed
     */
    private $to;

    /**
     * BetweenQuery constructor.
     *
     * @param null $from
     * @param null $to
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

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     * @param string $operatorFrom
     */
    public function setFrom($from, $operatorFrom = self::OPERATOR_EQUAL_THAN)
    {
        $this->operatorFrom = $operatorFrom;
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
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
}