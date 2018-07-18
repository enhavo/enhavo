<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 18.05.18
 * Time: 16:21
 */

namespace Enhavo\Bundle\NavigationBundle\Factory;

use Enhavo\Bundle\AppBundle\DynamicForm\FactoryInterface;

class Factory implements FactoryInterface
{
    /**
     * @var string
     */
    private $class;

    public function __construct($class)
    {
        $this->class = $class;
    }

    public function createNew()
    {
        $class = $this->class;
        return new $class();
    }
}