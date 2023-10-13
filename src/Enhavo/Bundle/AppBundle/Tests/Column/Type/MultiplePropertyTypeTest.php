<?php

namespace Enhavo\Bundle\AppBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Column\Type\MultiplePropertyType;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class MultiplePropertyTypeTest extends AbstractTypeTestCase
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
