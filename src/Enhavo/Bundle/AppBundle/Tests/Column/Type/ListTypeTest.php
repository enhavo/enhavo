<?php

namespace Enhavo\Bundle\AppBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Column\Type\ListType;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class ListTypeTest extends AbstractTypeTestCase
{
    public function testInitialize()
    {
        $type = new ListType();
        $this->assertInstanceOf(ListType::class, $type);
        $this->assertEquals('list', $type->getType());
    }

    public function testCreateResourceView()
    {
        $column = $this->createColumn(new ListType(), [
            'property' => 'entities',
            'item_property' => 'name',
            'separator' => '-'
        ]);

        $entity = $this->getEntityMock();
        $value = $column->createResourceViewData($entity);
        $this->assertEquals('one-two', $value);
    }

    private function getEntityMock()
    {
        $childOne = new EntityMock();
        $childOne->setName('one');

        $childTwo = new EntityMock();
        $childTwo->setName('two');

        $entity = new EntityMock();
        $entity->addEntity($childOne);
        $entity->addEntity($childTwo);

        return $entity;
    }
}
