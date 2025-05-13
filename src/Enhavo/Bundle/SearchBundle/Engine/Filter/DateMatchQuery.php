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

class DateMatchQuery extends MatchQuery
{
    public function __construct(?\DateTime $value = null, $operator = self::OPERATOR_EQUAL)
    {
        if (null != $value) {
            $value = $value->getTimestamp();
        }
        parent::__construct($value, $operator);
    }

    /**
     * @param bool $value
     */
    public function setValue($value)
    {
        if ($value instanceof \DateTime) {
            parent::setValue($value->getTimestamp());
        } else {
            parent::setValue(null);
        }
    }
}
