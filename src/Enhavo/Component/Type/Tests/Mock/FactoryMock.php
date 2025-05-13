<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
