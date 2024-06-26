<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\ResourceBundle\Column\Type\PositionColumnType;

class PositionTypeTest extends AbstractTypeTestCase
{
    function testInitialize()
    {
        $type = new PositionColumnType();
        $this->assertInstanceOf(PositionColumnType::class, $type);
        $this->assertEquals('position', $type->getType());
    }

    function testCreateResourceView()
    {
        $column = $this->createColumn(new PositionColumnType(), []);
        $entity = new EntityMock();
        $value = $column->createResourceViewData($entity);
        $this->assertNull($value);
    }
}
