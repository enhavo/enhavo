<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 13.02.18
 * Time: 00:58
 */

namespace Enhavo\Bundle\AppBundle\Form\Config;


class Condition
{
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
     * @return ConditionObserver
     */
    public function createObserver($values)
    {
        if(!is_array($values)) {
            $values = [$values];
        }

        $observer = new ConditionObserver($this, $values);
        return $observer;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}