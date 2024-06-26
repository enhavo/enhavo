<?php

namespace Enhavo\Bundle\ResourceBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;
use Enhavo\Bundle\ResourceBundle\Column\Type\BaseColumnType;

class TemplateTypeTest extends AbstractTypeTestCase
{
    function testInitialize()
    {
        $type = new BaseColumnType();
        $this->assertInstanceOf(BaseColumnType::class, $type);
        $this->assertEquals('template', $type->getType());
    }

    function testCreateResourceView()
    {
        $column = $this->createColumn(new BaseColumnType(), [
            'property' => 'data'
        ]);
        $entity = new EntityMock();
        $entity->setData('Test');
        $value = $column->createResourceViewData($entity);
        $this->assertEquals('Test', $value);
    }
}
