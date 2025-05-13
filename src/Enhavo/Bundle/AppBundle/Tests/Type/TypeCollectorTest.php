<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\AppBundle\Type;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TypeCollectorTest extends TestCase
{
    private function getContainerMock()
    {
        $mock = $this->getMockBuilder(ContainerInterface::class)->getMock();
        $mock->method('get')->willReturnCallback(function ($id) {
            if ('id1' == $id) {
                return $this->getTypeInterfaceMock($id);
            }
            if ('id2' == $id) {
                return $this->getTypeInterfaceMock($id);
            }
        });

        return $mock;
    }

    private function getTypeInterfaceMock($id)
    {
        $mock = $this->getMockBuilder(TypeInterface::class)->getMock();
        $mock->method('getType')->willReturnCallback(function () use ($id) {
            if ('id1' == $id) {
                return 'alias1';
            }
            if ('id2' == $id) {
                return 'alias2';
            }
        });

        return $mock;
    }

    public function testInitialize()
    {
        $collector = new TypeCollector($this->getContainerMock(), 'typeName');
        $this->assertInstanceOf(TypeCollector::class, $collector);
    }

    public function testTypeCollector()
    {
        $collector = new TypeCollector($this->getContainerMock());
        $collector->add('alias1', 'id1');
        $collector->add('alias2', 'id2');

        $type1 = $collector->getType('alias1');
        $type2 = $collector->getType('alias2');

        static::assertInstanceOf(TypeInterface::class, $type1);
        static::assertInstanceOf(TypeInterface::class, $type2);

        $this->assertEquals('alias1', $type1->getType('id1'));
        $this->assertEquals('alias2', $type2->getType('id2'));
    }
}
