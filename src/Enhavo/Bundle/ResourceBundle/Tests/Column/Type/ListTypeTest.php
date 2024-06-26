<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\ResourceBundle\Column\Type\ListColumnType;

class ListTypeTest extends AbstractTypeTestCase
{
    public function testInitialize()
    {
        $type = new ListColumnType();
        $this->assertInstanceOf(ListColumnType::class, $type);
        $this->assertEquals('list', $type->getType());
    }

    public function testCreateResourceView()
    {
        $column = $this->createColumn(new ListColumnType(), [
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
