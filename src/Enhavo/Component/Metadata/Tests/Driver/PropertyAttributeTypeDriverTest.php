<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Tests\Driver;

use Enhavo\Component\Metadata\Driver\PropertyAttributeTypeDriver;
use Enhavo\Component\Metadata\Tests\Fixtures\AttributeClass;
use Enhavo\Component\Metadata\Tests\Fixtures\AttributeTestClass;
use PHPUnit\Framework\TestCase;

class PropertyAttributeTypeDriverTest extends TestCase
{
    public function createDependencies()
    {
        $dependencies = new AttributeDriverDependencies();

        return $dependencies;
    }

    public function createInstance(AttributeDriverDependencies $dependencies)
    {
        $instance = new PropertyAttributeTypeDriver(
            AttributeClass::class,
        );

        return $instance;
    }

    public function testGetProperties()
    {
        $dependencies = $this->createDependencies();
        $instance = $this->createInstance($dependencies);

        $values = $instance->loadClass(AttributeTestClass::class);

        $this->assertEquals([
            'option1' => 'valueOption1',
            'type' => 'myType',
        ], $values['property']);
    }
}

class AttributeDriverDependencies
{
}
