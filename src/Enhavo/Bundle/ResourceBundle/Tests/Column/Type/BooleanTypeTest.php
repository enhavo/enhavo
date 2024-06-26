<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\ResourceBundle\Column\Type\BooleanColumnType;

class BooleanTypeTest extends AbstractTypeTestCase
{
    public function testInitialize()
    {
        $type = new BooleanColumnType();
        $this->assertInstanceOf(BooleanColumnType::class, $type);
        $this->assertEquals('boolean', $type->getType());
    }

    public function testCreateResourceView()
    {
        $column = $this->createColumn(new BooleanColumnType(), [
            'property' => 'name'
        ]);

        $entity = new EntityMock();
        $entity->setName('Test');
        $value = $column->createResourceViewData($entity);
        $this->assertTrue($value);

        $entity->setName(null);
        $value = $column->createResourceViewData($entity);
        $this->assertFalse($value);
    }
}
