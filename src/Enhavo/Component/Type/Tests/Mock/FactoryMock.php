<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 2020-06-08
 * Time: 13:10
 */

namespace Enhavo\Component\Type\Tests\Mock;

use Enhavo\Component\Type\FactoryInterface;

class FactoryMock implements FactoryInterface
{
    /** @var string */
    private $class;

    /** @var RegistryMock */
    private $registry;

    /**
     * FactoryMock constructor.
     * @param $class
     */
    public function __construct($class)
    {
        $this->class = $class;
        $this->registry = new RegistryMock();
    }

    public function register($name, $type)
    {
        return $this->registry->register($name, $type);
    }

    public function create(array $options)
    {
        return new $this->class($options, $this->registry->getType($options['type']), []);
    }
}
