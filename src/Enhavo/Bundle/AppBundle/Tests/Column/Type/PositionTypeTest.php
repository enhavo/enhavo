<?php

namespace Enhavo\Bundle\AppBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Column\Type\PositionType;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class PositionTypeTest extends AbstractTypeTestCase
{
    function testInitialize()
    {
        $type = new PositionType();
        $this->assertInstanceOf(PositionType::class, $type);
        $this->assertEquals('position', $type->getType());
    }

    function testCreateResourceView()
    {
        $column = $this->createColumn(new PositionType(), []);
        $entity = new EntityMock();
        $value = $column->createResourceViewData($entity);
        $this->assertNull($value);
    }
}
