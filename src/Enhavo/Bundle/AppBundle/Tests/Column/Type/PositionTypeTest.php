<?php

namespace Enhavo\Bundle\AppBundle\Column\Type;

use Enhavo\Bundle\AppBundle\Mock\EntityMock;

class PositionTypeTest extends AbstractTypeTest
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
