<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\ResourceBundle\Column\Type\MultiplePropertyColumnType;

class MultiplePropertyTypeTest extends AbstractTypeTestCase
{
    function testInitialize()
    {
        $type = new MultiplePropertyColumnType();
        $this->assertInstanceOf(MultiplePropertyColumnType::class, $type);
        $this->assertEquals('multiple_property', $type->getType());
    }

    public function testCreateResourceView()
    {
        $column = $this->createColumn(new MultiplePropertyColumnType(), [
            'properties' => [
                'name',
                'id'
            ],
            'separator' => '/'
        ]);

        $entity = new EntityMock();
        $entity->setName('name');
        $entity->setId('id');
        $value = $column->createResourceViewData($entity);
        $this->assertEquals('name/id', $value);
    }
}
