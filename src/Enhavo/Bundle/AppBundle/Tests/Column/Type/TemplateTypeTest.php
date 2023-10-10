<?php

namespace Enhavo\Bundle\AppBundle\Tests\Column\Type;

use Enhavo\Bundle\AppBundle\Column\Type\TemplateType;
use Enhavo\Bundle\AppBundle\Tests\Mock\EntityMock;

class TemplateTypeTest extends AbstractTypeTestCase
{
    function testInitialize()
    {
        $type = new TemplateType();
        $this->assertInstanceOf(TemplateType::class, $type);
        $this->assertEquals('template', $type->getType());
    }

    function testCreateResourceView()
    {
        $column = $this->createColumn(new TemplateType(), [
            'property' => 'data'
        ]);
        $entity = new EntityMock();
        $entity->setData('Test');
        $value = $column->createResourceViewData($entity);
        $this->assertEquals('Test', $value);
    }
}
