<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Component\Metadata\Tests\Extension;

use Enhavo\Component\Metadata\Extension\Property;
use Enhavo\Component\Metadata\Extension\PropertyInterface;
use Enhavo\Component\Metadata\Extension\PropertyTrait;
use PHPUnit\Framework\TestCase;

class PropertyTraitTest extends TestCase
{
    public function testGetterAndSetter()
    {
        $trait = new PropertyTraitClass();

        $trait->addProperty(new Property('name'));
        $trait->addProperty(new Property('type'));
        $this->assertCount(2, $trait->getProperties());

        $this->assertTrue($trait->hasProperty('name'));
        $this->assertFalse($trait->hasProperty('anything'));

        $this->assertEquals('name', $trait->getProperty('name')->getName());
    }
}

class PropertyTraitClass implements PropertyInterface
{
    use PropertyTrait;
}
