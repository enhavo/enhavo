<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29.08.18
 * Time: 18:49
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Filter;

class DateBetweenQuery extends BetweenQuery
{
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
        if($from instanceof \DateTime) {
            $from = $from->getTimestamp();
        }
        if($to instanceof \DateTime) {
            $to = $to->getTimestamp();
        }
        parent::__construct($from, $to, $operatorFrom, $operatorTo);
    }

    /**
     * @param mixed $from
     * @param string $operatorFrom
     */
    public function setFrom($from, $operatorFrom = self::OPERATOR_EQUAL_THAN)
    {
        if($from instanceof \DateTime) {
            $from = $from->getTimestamp();
        }
        $this->setOperatorFrom($operatorFrom);
        parent::setFrom($from);
    }

    /**
     * @param mixed $to
     * @param string $operatorTo
     */
    public function setTo($to, $operatorTo = self::OPERATOR_EQUAL_THAN)
    {
        if($to instanceof \DateTime) {
            $to = $to->getTimestamp();
        }
        $this->setOperatorTo($operatorTo);
        parent::setTo($to);
    }
}