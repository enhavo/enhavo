<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.02.18
 * Time: 00:58
 */

namespace Enhavo\Bundle\FormBundle\Form\Config;


class Condition
{
    const AND = 'and';
    const OR = 'or';

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
     * @param string $operator
     * @param string|null $scope
     * @return ConditionObserver
     */
    public function createObserver($values, $scope = null, $operator = self::AND)
    {
        if(!is_array($values)) {
            $values = [$values];
        }
        foreach($values as &$value) {
            $value = (string)$value;
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
