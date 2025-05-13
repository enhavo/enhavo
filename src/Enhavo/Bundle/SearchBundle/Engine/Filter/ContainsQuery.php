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

class ContainsQuery implements QueryInterface
{
    /**
     * @var array[]
     */
    private $values = [];

    public function __construct(?array $values = null)
    {
        if (null !== $values) {
            $this->values = $values;
        }
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues($values)
    {
        if (null !== $values) {
            $this->values = $values;
        }
    }
}
