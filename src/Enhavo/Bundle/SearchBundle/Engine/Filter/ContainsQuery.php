<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29.08.18
 * Time: 18:48
 */

namespace Enhavo\Bundle\SearchBundle\Engine\Filter;


class ContainsQuery implements QueryInterface
{
    /**
     * @var array[]
     */
    private $values = [];

    public function __construct(array $values = null)
    {
        if($values !== null) {
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
        if($values !== null) {
            $this->values = $values;
        }
    }
}