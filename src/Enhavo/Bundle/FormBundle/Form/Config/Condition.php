<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\FormBundle\Form\Config;

class Condition
{
    public const AND = 'and';
    public const OR = 'or';

    /**
     * @var string
     */
    private $id;

    public function __construct()
    {
        $this->id = uniqid();
    }

    /**
     * @param array|string|null $values
     * @param string            $operator
     * @param string|null       $scope
     *
     * @return ConditionObserver
     */
    public function createObserver($values, $scope = null, $operator = self::AND)
    {
        if (!is_array($values)) {
            $values = [$values];
        }
        foreach ($values as &$value) {
            $value = (string) $value;
        }

        return new ConditionObserver($this, $values, $operator, $scope);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
