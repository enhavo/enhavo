<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29.08.18
 * Time: 18:48
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Filter;


class DateMatchQuery extends MatchQuery
{
    public function __construct(\DateTime $value = null, $operator = self::OPERATOR_EQUAL)
    {
        if($value != null) {
            $value = $value->getTimestamp();
        }
        parent::__construct($value, $operator);
    }

    /**
     * @param bool $value
     */
    public function setValue($value)
    {
        if($value instanceof \DateTime) {
            parent::setValue($value->getTimestamp());
        } else {
            parent::setValue(null);
        }
    }
}