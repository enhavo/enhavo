<?php

namespace Enhavo\Bundle\AppBundle\Column\Type;

use Enhavo\Bundle\AppBundle\Mock\EntityMock;

class MultipleTypeTest extends AbstractTypeTest
{
    function testInitialize()
    {
        $type = new MultiplePropertyType();
        $this->assertInstanceOf(MultiplePropertyType::class, $type);
        $this->assertEquals('multiple_property', $type->getType());
    }

    public function testCreateResourceView()
    {
        $column = $this->createColumn(new MultiplePropertyType(), [
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
